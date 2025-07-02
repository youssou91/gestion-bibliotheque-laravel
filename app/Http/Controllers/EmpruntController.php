<?php

namespace App\Http\Controllers;

use App\Models\Amende;
use App\Models\Emprunt;
use App\Models\Ouvrages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpruntController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'livre_id' => 'required|exists:ouvrages,id',
        ]);
        $empruntEnCours = Emprunt::where('utilisateur_id', auth()->id())
            ->where('ouvrage_id', $request->livre_id)
            ->where('statut', 'en_cours')
            ->exists();
        if ($empruntEnCours) {
            return redirect()->back()->with('error', 'Vous avez déjà un emprunt en cours pour ce livre.');
        }
        $dateRetour = now()->addDays(14);

        Emprunt::create([
            'utilisateur_id' => auth()->id(),
            'ouvrage_id' => $request->livre_id,
            'date_emprunt' => now(),
            'date_retour' => $dateRetour,
            'statut' => 'en_cours',
        ]);

        $livre = Ouvrages::find($request->livre_id);
        if ($livre->stock && $livre->stock->quantite > 0) {
            $livre->stock->decrement('quantite');
        }

        return redirect()->back()->with('success', 'Livre emprunté avec succès.');
    }
    // public function retour($id)
    // {
    //     $emprunt = Emprunt::findOrFail($id);
    //     $today = now();
    //     $retard = $today->greaterThan($emprunt->date_retour);
    //     $amende = 0;
    //     if ($retard) {
    //         $jours = $today->diffInDays($emprunt->date_retour);
    //         $amende = $jours * 1.50;
    //     }
    //     $emprunt->update([
    //         'date_effective_retour' => $today,
    //         'statut' => 'retourne',
    //         'amende' => $amende
    //     ]);
    //     if ($emprunt->ouvrage->stock) {
    //         $emprunt->ouvrage->stock->increment('quantite');
    //     }
    //     return back()->with('success', 'Livre retourné. Amende : $' . number_format($amende, 2));
    // }
    public function retour($id)
    {
        $emprunt = Emprunt::findOrFail($id);
        $today = now();
        $retard = $today->greaterThan($emprunt->date_retour);
        $amende = 0;

        if ($retard) {
            $jours = $today->diffInDays($emprunt->date_retour);
            $amende = $jours * 1.50;

            // Création de l'amende dans la nouvelle table
            Amende::create([
                'utilisateur_id' => $emprunt->utilisateur_id,
                'ouvrage_id' => $emprunt->ouvrage_id,
                'emprunt_id' => $emprunt->id,
                'montant' => $amende,
                'date_amende' => $today,
                'est_payee' => false, // Par défaut, l'amende n'est pas payée
                'motif' => 'Retard de ' . $jours . ' jour(s) sur le retour'
            ]);
        }

        // Mise à jour de l'emprunt
        $emprunt->update([
            'date_effective_retour' => $today,
            'statut' => 'retourne',
            'amende' => $amende // Vous pouvez conserver ce champ ou le supprimer si vous ne l'utilisez plus
        ]);

        // Réapprovisionnement du stock
        if ($emprunt->ouvrage->stock) {
            $emprunt->ouvrage->stock->increment('quantite');
        }

        return back()->with('success', 'Livre retourné. ' . ($retard ? 'Amende : $' . number_format($amende, 2) : 'Pas d\'amende à payer'));
    }
    public function index()
    {
        $emprunts = Emprunt::with(['ouvrage', 'utilisateur'])
            ->orderBy('date_emprunt', 'desc')
            ->get();

        return view('Suivie_ventes', compact('emprunts'));
    }
    public function nbEmprunts()
    {
        $countEmprunts = Emprunt::where('utilisateur_id', auth()->id())
            ->where('statut', 'en_cours')
            ->count();
        dd($countEmprunts);
        return $countEmprunts;
    }

    public function mesEmprunts()
    {
        $this->verifierRetards();  // Appelle la fonction avant de récupérer les emprunts

        $utilisateurId = auth()->id();

        $empruntsEnCours = Emprunt::with('ouvrage')
            ->where('utilisateur_id', $utilisateurId)
            ->whereIn('statut', ['en_cours', 'en_retard'])  // inclut en retard
            ->orderBy('date_emprunt', 'desc')
            ->get();

        $empruntsHistorique = Emprunt::with('ouvrage')
            ->where('utilisateur_id', $utilisateurId)
            ->whereNotIn('statut', ['en_cours', 'en_retard'])
            ->orderBy('date_retour', 'desc')
            ->paginate(10);

        return view('frontOffice.emprunts', compact('empruntsEnCours', 'empruntsHistorique'));
    }

    private function verifierRetards()
    {
        Emprunt::where('statut', 'en_cours')
            ->whereDate('date_retour', '<', now())
            ->update(['statut' => 'en_retard']);
    }


    public function favoris()
    {
        $favoris = Auth::user();
        return view('frontOffice.favoris', compact('favoris'));
    }
    // ---------------------------------------
    public function adminDashboard()
    {
        // Statistiques principales
        $empruntsMois = Emprunt::whereMonth('date_emprunt', now()->month)->count();
        $empruntsSemaine = Emprunt::whereBetween('date_emprunt', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $retards = Emprunt::where('statut', 'en_cours')
            ->where('date_retour', '<', now())
            ->count();

        //     // Top ouvrage
        $topOuvrage = Ouvrages::withCount('emprunts')
            ->orderByDesc('emprunts_count')
            ->first();

        //     // Données pour les graphiques
        $dailyData = Emprunt::selectRaw('DATE(date_emprunt) as date, COUNT(*) as count')
            ->whereBetween('date_emprunt', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        $dailyDates = Emprunt::selectRaw('DATE(date_emprunt) as date')
            ->whereBetween('date_emprunt', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('date')
            ->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m'))
            ->toArray();

        //     // Derniers emprunts
        $lastEmprunts = Emprunt::with('ouvrage', 'utilisateur')
            ->orderByDesc('date_emprunt')
            ->take(10)
            ->get();

        return view('admin.dashboard', [
            'empruntsMois' => $empruntsMois,
            'empruntsSemaine' => $empruntsSemaine,
            'retards' => $retards,
            'topOuvrage' => $topOuvrage->titre ?? 'Aucun',
            'topEmprunts' => $topOuvrage->emprunts_count ?? 0,
            'dailyData' => $dailyData,
            'dailyDates' => $dailyDates,
            'lastEmprunts' => $lastEmprunts,
            'catLabels' => ['En cours', 'Retard', 'Retournés'], // Exemple de catégories
            'catData' => [
                Emprunt::where('statut', 'en_cours')->count(),
                Emprunt::where('statut', 'en_cours')->where('date_retour', '<', now())->count(),
                Emprunt::where('statut', 'retourne')->count()
            ]
        ]);
    }
}
