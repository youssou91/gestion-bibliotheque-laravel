<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Emprunt;
use App\Models\Ouvrages;
use App\Models\Utilisateurs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $reservationsActives = Reservation::with('ouvrage')
            ->where('utilisateur_id', $userId)
            ->whereIn('statut', ['en_attente', 'validee'])
            ->get();

        $reservationsHistorique = Reservation::with('ouvrage')
            ->where('utilisateur_id', $userId)
            ->whereIn('statut', ['refusee', 'annulee']) // adapte selon tes valeurs possibles
            ->paginate(10);

        return view('frontOffice.reservations', compact('reservationsActives', 'reservationsHistorique'));
    }

    // ✅ Client crée une réservation
    public function store(Request $request)
    {
        $request->validate([
            'ouvrage_id' => 'required|exists:ouvrages,id',
        ]);

        $userId = Auth::id();
        $ouvrageId = $request->ouvrage_id;

        // 🚫 Vérifie si un emprunt en cours existe (non retourné)
        $empruntEnCours = Emprunt::where('ouvrage_id', $ouvrageId)
            ->where('utilisateur_id', $userId)
            ->where(function ($query) {
                $query->where('statut', '!=', 'retourne')
                    ->orWhereNull('date_effective_retour');;
            })
            ->exists();

        if ($empruntEnCours) {
            return back()->with('error', 'Vous ne pouvez pas réserver ce livre tant que vous ne l’avez pas retourné.');
        }

        // 🔁 Vérifie s’il existe une réservation active
        $reservationExistante = Reservation::where('ouvrage_id', $ouvrageId)
            ->where('utilisateur_id', $userId)
            ->whereIn('statut', ['en_attente', 'validee'])
            ->exists();

        if ($reservationExistante) {
            return back()->with('error', 'Vous avez déjà une réservation active pour ce livre.');
        }

        // 📚 Limite de 3 réservations en attente
        $nbReservationsEnAttente = Reservation::where('utilisateur_id', $userId)
            ->where('statut', 'en_attente')
            ->count();

        if ($nbReservationsEnAttente >= 3) {
            return back()->with('error', 'Vous avez atteint la limite de 3 réservations en attente.');
        }

        // 📦 Vérifie le stock
        $ouvrage = Ouvrages::with('stock')->findOrFail($ouvrageId);
        if (!$ouvrage->stock || $ouvrage->stock->quantite <= 0) {
            return back()->with('error', 'Cet ouvrage est actuellement indisponible pour réservation.');
        }

        // ✅ Crée la réservation
        Reservation::create([
            'ouvrage_id' => $ouvrageId,
            'utilisateur_id' => $userId,
            'date_reservation' => now(),
        ]);

        return back()->with('success', 'Réservation enregistrée. En attente de validation.');
    }

    // ✅ Admin liste toutes les réservations
    public function indexAdmin()
    {
        // Récupération de toutes les réservations avec les relations nécessaires
        $reservations = Reservation::with([
            'ouvrage',
            'utilisateur' => function ($query) {
                // Si vous utilisez SoftDeletes et voulez inclure les utilisateurs supprimés
                if (method_exists($query->getRelated(), 'trashed')) {
                    $query->withTrashed();
                }
            }
        ])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Calcul des statistiques
        $stats = [
            'total' => Reservation::count(),
            'confirmees' => Reservation::where('statut', 'confirmee')->count(),
            'en_attente' => Reservation::where('statut', 'en_attente')->count(),
            'annulees' => Reservation::where('statut', 'annulee')->count(),
        ];

        // Pour le formulaire
        // $clients = Utilisateurs::where('role', 'client')->get();
        // $ouvrages = Ouvrages::when(method_exists(Ouvrages::class, 'scopeDisponibles'), function ($query) {
        //     $query->disponibles();
        // })->get();

        return view('reservations', compact('reservations', 'stats'));
    }

    // ✅ Client voit ses réservations validées
    public function mesReservations()
    {
        $reservations = Reservation::where('utilisateur_id', Auth::id())
            ->where('statut', 'validee')
            ->get();

        return view('client.reservations.index', compact('reservations'));
    }
    // ✅ Client récupère l’ouvrage → devient un emprunt
    public function recuperer($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->statut === 'validee') {
            Emprunt::create([
                'utilisateur_id' => $reservation->utilisateur_id,
                'ouvrage_id' => $reservation->ouvrage_id,
                'date_emprunt' => now(),
                'date_retour_prevue' => now()->addDays(14),
            ]);

            $reservation->delete();

            return back()->with('success', 'Réservation récupérée. Emprunt enregistré.');
        }

        return back()->with('error', 'Réservation non validée.');
    }
    // ✅ Client annule une réservation
    public function annuler(Reservation $reservation)
    {
        if ($reservation->utilisateur_id !== Auth::id()) {
            abort(403); // accès interdit
        }
        // Vérifie si la réservation est annulable
        // Seules les réservations en attente ou validées peuvent être annulées

        if (!in_array($reservation->statut, ['en_attente'])) {
            return back()->with('error', 'Cette réservation ne peut pas être annulée.');
        }

        $reservation->update(['statut' => 'annulee']);

        return back()->with('success', 'Réservation annulée avec succès.');
    }
    // ✅ Admin annule une réservation
    public function annulerAdmin($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['statut' => 'annulee']);

        return back()->with('success', 'Réservation annulée avec succès.');
    }
    // Valider une réservation => crée un emprunt
    public function valider($id)
    {
        $reservation = Reservation::with('ouvrage.stock')->findOrFail($id);

        if ($reservation->statut !== 'en_attente') {
            return back()->with('error', 'Cette réservation a déjà été traitée.');
        }

        $stock = $reservation->ouvrage->stock;
        if (!$stock || $stock->quantite < 1) {
            return back()->with('error', 'Stock insuffisant pour valider cette réservation.');
        }

        DB::beginTransaction();

        try {
            // Mettre à jour la réservation
            $reservation->statut = 'validee';
            $reservation->save();

            // Créer l'emprunt
            Emprunt::create([
                'utilisateur_id' => $reservation->utilisateur_id,
                'ouvrage_id' => $reservation->ouvrage_id,
                'date_emprunt' => now(),
                'date_retour' => now()->addDays(14),
                'statut' => 'en_cours'
            ]);

            // Décrémenter le stock
            $stock->decrement('quantite');

            DB::commit();
            return back()->with('success', 'Réservation validée et emprunt créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la validation.');
        }
    }
}
