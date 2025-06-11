<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Ligne_ventes;
use App\Models\Ouvrages;
use App\Models\Ventes;
use Illuminate\Http\Request;

class GestController extends Controller
{
    // private function getDashboardData()
    // {
    //     $ventesMois = Ventes::whereMonth('created_at', now()->month)
    //         ->whereYear('created_at', now()->year)
    //         ->count();

    //     $CA = Ventes::whereMonth('created_at', now()->month)
    //         ->whereYear('created_at', now()->year)
    //         ->sum('montant_total');

    //     $panierMoyen = $ventesMois > 0 ? round($CA / $ventesMois, 2) : 0;

    //     $top = Ligne_ventes::with('ouvrage')
    //         ->selectRaw('ouvrage_id, SUM(quantite) as total_qty')
    //         ->whereMonth('created_at', now()->month)
    //         ->whereYear('created_at', now()->year)
    //         ->groupBy('ouvrage_id')
    //         ->orderByDesc('total_qty')
    //         ->first();

    //     $topLivre = $top && $top->ouvrage ? $top->ouvrage->titre : '-';
    //     $topVentes = $top ? $top->total_qty : 0;

    //     $start = now()->subDays(29)->startOfDay();
    //     $rawDaily = Ligne_ventes::selectRaw('DATE(created_at) as date, SUM(quantite) as qty')
    //         ->where('created_at', '>=', $start)
    //         ->groupBy('date')
    //         ->orderBy('date')
    //         ->pluck('qty', 'date')
    //         ->toArray();

    //     $dailyDates = collect(range(0, 29))
    //         ->map(fn($i) => now()->subDays(29 - $i)->format('d/m'))
    //         ->toArray();

    //     $dailyData = collect(range(0, 29))
    //         ->map(fn($i) => $rawDaily[now()->subDays(29 - $i)->format('Y-m-d')] ?? 0)
    //         ->toArray();

    //     $lines = Ligne_ventes::with('ouvrage.categorie')
    //         ->where('created_at', '>=', $start)
    //         ->get();

    //     $catMap = $lines
    //         ->groupBy(fn($l) => $l->ouvrage->categorie->nom ?? 'Autre')
    //         ->map->sum('quantite');

    //     $catLabels = $catMap->keys()->toArray();
    //     $catData   = $catMap->values()->toArray();

    //     $lastLines = Ligne_ventes::with(['vente', 'ouvrage', 'utilisateur'])
    //         ->orderByDesc('created_at')
    //         ->limit(10)
    //         ->get();

    //     return compact(
    //         'ventesMois',
    //         'CA',
    //         'panierMoyen',
    //         'topLivre',
    //         'topVentes',
    //         'dailyDates',
    //         'dailyData',
    //         'catLabels',
    //         'catData',
    //         'lastLines'
    //     );
    // }
    private function getDashboardData()
    {
        // Statistiques principales
        $empruntsMois = Emprunt::whereMonth('created_at', now()->month)->count();
        $empruntsSemaine = Emprunt::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $retards = Emprunt::where('statut', 'en_cours')
            ->where('date_retour', '<', now())
            ->count();

        // Top ouvrage
        $topOuvrage = Ouvrages::withCount('emprunts')
            ->orderByDesc('emprunts_count')
            ->first();

        // Données pour les graphiques (30 derniers jours)
        $startDate = now()->subDays(29)->startOfDay();

        // Graphique linéaire (emprunts par jour)
        $dailyData = Emprunt::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $dailyDates = collect(range(0, 29))
            ->map(fn($i) => now()->subDays(29 - $i)->format('d/m'))
            ->toArray();

        $dailyCounts = collect(range(0, 29))
            ->map(fn($i) => $dailyData[now()->subDays(29 - $i)->format('Y-m-d')] ?? 0)
            ->toArray();

        // Graphique circulaire (répartition par statut)
        $statuts = Emprunt::selectRaw('statut, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('statut')
            ->pluck('count', 'statut')
            ->toArray();

        $statutLabels = [
            'en_cours' => 'En cours',
            'retourne' => 'Retournés',
            'retard' => 'En retard'
        ];

        $statutData = [];
        foreach ($statutLabels as $key => $label) {
            $statutData[$label] = $statuts[$key] ?? 0;
        }

        // Derniers emprunts
        $lastEmprunts = Emprunt::with(['ouvrage', 'utilisateur'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return [
            'empruntsMois' => $empruntsMois,
            'empruntsSemaine' => $empruntsSemaine,
            'retards' => $retards,
            'topOuvrage' => $topOuvrage->titre ?? 'Aucun',
            'topEmprunts' => $topOuvrage->emprunts_count ?? 0,
            'dailyDates' => $dailyDates,
            'dailyData' => $dailyCounts,
            'statutLabels' => array_keys($statutData),
            'statutData' => array_values($statutData),
            'lastEmprunts' => $lastEmprunts
        ];
    }

    public function index()
    {
        return view('Suivie_ventes', $this->getDashboardData());
    }

    public function adminDashboard()
    {
        return view('admin.dashboard', $this->getDashboardData());
    }

    public function show(Ligne_ventes $lignevente)
    {
        $lignevente->load(['vente', 'ouvrage', 'utilisateur']);
        return response()->json($lignevente);
    }
    public function gererCatalogue()
    {
        return view('gerer_catalogue');
    }

    public function suivreVentes()
    {
        return view('Suivie_ventes');
    }
}
