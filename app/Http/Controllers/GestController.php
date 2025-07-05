<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Ouvrage;
use App\Models\Ouvrages;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GestController extends Controller
{
    public function index()
    {
        return $this->adminDashboard();
    }

    private function verifierRetards()
    {
        Emprunt::where('statut', 'en_cours')
            ->whereDate('date_retour', '<', now())
            ->where('statut', '!=', 'en_retard')
            ->update(['statut' => 'en_retard']);
    }

    public function adminDashboard()
    {
        $this->verifierRetards();

        // Statistiques principales
        $empruntsMois = Emprunt::whereMonth('created_at', now()->month)->count();
        $empruntsSemaine = Emprunt::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $retards = Emprunt::where('statut', 'en_retard')->count();
        $empruntsEnCours = Emprunt::where('statut', 'en_cours')->count();

        // Top ouvrage
        $topOuvrage = Ouvrages::withCount('emprunts')
            ->orderByDesc('emprunts_count')
            ->first();

        // Données pour les graphiques (30 derniers jours)
        $dailyData = $this->getDailyEmpruntsData();
        $statutData = $this->getStatutRepartitionData();

        // Derniers emprunts avec pagination
        $lastEmprunts = Emprunt::with(['ouvrage', 'utilisateur'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.dashboard', [
            'empruntsMois' => $empruntsMois,
            'empruntsSemaine' => $empruntsSemaine,
            'retards' => $retards,
            'empruntsEnCours' => $empruntsEnCours,
            'topOuvrage' => $topOuvrage->titre ?? 'Aucun',
            'topEmprunts' => $topOuvrage->emprunts_count ?? 0,
            'dailyDates' => $dailyData['dates'],
            'dailyData' => $dailyData['counts'],
            'statutLabels' => $statutData['labels'],
            'statutData' => $statutData['values'],
            'lastEmprunts' => $lastEmprunts
        ]);
    }

    private function getDailyEmpruntsData($days = 30)
    {
        $startDate = now()->subDays($days - 1)->startOfDay();

        $data = Emprunt::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $dates = collect(range(0, $days - 1))
            ->map(fn($i) => now()->subDays($days - 1 - $i)->format('d/m'))
            ->toArray();

        $counts = collect(range(0, $days - 1))
            ->map(fn($i) => $data[now()->subDays($days - 1 - $i)->format('Y-m-d')] ?? 0)
            ->toArray();

        return [
            'dates' => $dates,
            'counts' => $counts
        ];
    }

    private function getStatutRepartitionData()
    {
        $stats = Emprunt::selectRaw('
        SUM(CASE WHEN statut = "retourne" THEN 1 ELSE 0 END) as retourne,
        SUM(CASE WHEN statut = "en_cours" AND date_retour >= NOW() THEN 1 ELSE 0 END) as en_cours,
        SUM(CASE WHEN statut = "en_retard" AND date_retour < NOW() THEN 1 ELSE 0 END) as en_retard
    ')->first();

        return [
            'labels' => ['En cours', 'Retournés', 'En retard'],
            'values' => [
                $stats->en_cours ?? 0,
                $stats->retourne ?? 0,
                $stats->en_retard ?? 0
            ]
        ];
    }

    public function filterEmprunts(Request $request)
    {
        $query = Emprunt::with(['ouvrage', 'utilisateur'])
            ->orderByDesc('created_at');

        // Filtre par statut
        if ($request->has('statut') && $request->statut != 'all') {
            if ($request->statut == 'en_retard') {
                $query->where('statut', 'en_cours')
                    ->where('date_retour', '<', now());
            } else {
                $query->where('statut', $request->statut);
            }
        }

        // Filtre par date
        if ($request->has('date_debut') && $request->date_debut) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->has('date_fin') && $request->date_fin) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        return response()->json([
            'emprunts' => $query->paginate(10),
            'stats' => $this->getFilteredStats($query)
        ]);
    }

    private function getFilteredStats($query)
    {
        $stats = $query->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN statut = "retourne" THEN 1 ELSE 0 END) as retourne,
            SUM(CASE WHEN statut = "en_cours" AND date_retour >= NOW() THEN 1 ELSE 0 END) as en_cours,
            SUM(CASE WHEN statut = "en_cours" AND date_retour < NOW() THEN 1 ELSE 0 END) as en_retard
        ')->first();

        return [
            'total' => $stats->total ?? 0,
            'retourne' => $stats->retourne ?? 0,
            'en_cours' => $stats->en_cours ?? 0,
            'en_retard' => $stats->en_retard ?? 0
        ];
    }

    public function show(Emprunt $emprunt)
    {
        $emprunt->load(['ouvrage', 'utilisateur']);
        return response()->json($emprunt);
    }

    public function retournerEmprunt(Request $request, Emprunt $emprunt)
    {
        $emprunt->update([
            'statut' => 'retourne',
            'date_retour_effectif' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Emprunt marqué comme retourné'
        ]);
    }

    public function prolongerEmprunt(Request $request, Emprunt $emprunt)
    {
        $validated = $request->validate([
            'date_retour' => 'required|date|after:' . $emprunt->date_retour
        ]);

        $emprunt->update([
            'date_retour' => $validated['date_retour']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Date de retour prolongée'
        ]);
    }
}
