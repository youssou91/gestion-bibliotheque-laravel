<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Emprunt;
use App\Models\Ouvrages;
use Illuminate\Support\Facades\Auth;

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
        $reservations = Reservation::with('ouvrage', 'user')->orderByDesc('created_at')->get();
        return view('admin.reservations.index', compact('reservations'));
    }
    // ✅ Admin valide une réservation
    public function valider($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['statut' => 'validee']);

        return back()->with('success', 'Réservation validée.');
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
}
