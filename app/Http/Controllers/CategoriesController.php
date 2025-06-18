<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //  Affiche l'interface de classification
    public function index()
    {
        $cats = Categories::with('children')
            ->withCount('ouvrages')
            ->whereNull('parent_id')
            ->get()
            ->each(fn($c) => $c->children->loadCount('ouvrages'));
        return view('classify_ouvrages', compact('cats'));
    }

    //  Formulaire de création
    public function create()
    {
        $parents = Categories::whereNull('parent_id')->get();
        return view('AjoutCategorie', compact('parents'));
    }

    // Enregistre une nouvelle catégorie
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'       => 'required|string|max:100',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Categories::create($data);
        return redirect()
            ->route('categories.index')
            ->with('success', 'Catégorie créée');
    }

    //  Formulaire d’édition
    public function edit($id)
    {
        $cat     = Categories::findOrFail($id);
        $parents = Categories::whereNull('parent_id')->where('id', '<>', $id)->get();
        return view('ModifierCategorie', compact('cat', 'parents'));
    }

    //  Met à jour
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nom'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'parent_id'  => 'nullable|exists:categories,id',
        ]);

        Categories::findOrFail($id)->update($data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Catégorie modifiée');
    }

    // Supprime
    public function destroy($id)
    {
        Categories::findOrFail($id)->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Catégorie supprimée');
    }

    /**
     * Affiche les ouvrages classés par catégories avec statistiques détaillées
     * 
     * @return \Illuminate\View\View
     */
    public function classifyOuvrages()
    {
        // Chargement optimisé des données sans les sous-catégories
        $categories = Categories::with([
            'ouvrages' => function ($query) {
                $query->select('id', 'titre', 'categorie_id')
                    ->orderBy('titre');
            }
        ])
            ->withCount('ouvrages as ouvrages_count')
            ->orderBy('nom')
            ->get();

        // Calcul des statistiques simplifiées
        $stats = [
            'totalOuvrages' => $categories->sum('ouvrages_count'),
            'totalCategories' => $categories->count(),
            'categoriesSansOuvrages' => $categories->filter(fn($c) => $c->ouvrages_count === 0)->count(),
            'categorieLaPlusRiche' => $categories->sortByDesc('ouvrages_count')->first(),
            'derniersOuvrages' => $categories->flatMap->ouvrages
                ->sortByDesc('created_at')
                ->take(5)
                ->values()
        ];

        return view('classify_ouvrages', [
            'categories' => $categories,
            'stats' => $stats,
            'chartData' => $this->prepareChartData($categories)
        ]);
    }
    // public function classifyOuvrages()
    // {
    //     // Chargement optimisé des données avec eager loading
    //     $categories = Categories::with([
    //         'children' => function ($query) {
    //             $query->withCount('ouvrages')
    //                 ->orderBy('nom');
    //         },
    //         'ouvrages' => function ($query) {
    //             $query->select('id', 'titre', 'id')
    //                 ->with('categories:id,nom')
    //                 ->latest(); // On ne prend que les 5 derniers ouvrages pour l'affichage
    //         },
    //         'children.ouvrages' => function ($query) {
    //             $query->select('id', 'titre', 'id')
    //                 ->latest(); // 3 derniers ouvrages par sous-catégorie
    //         }
    //     ])
    //         ->withCount(['ouvrages as ouvrages_count', 'children as children_count'])
    //         ->whereNull('parent_id')
    //         ->orderBy('nom')
    //         ->get();

    //     // Calcul des statistiques avancées
    //     $stats = [
    //         'totalOuvrages' => $categories->sum('ouvrages_count') +
    //             $categories->flatMap->children->sum('ouvrages_count'),
    //         'totalCategories' => $categories->count(),
    //         'totalSousCategories' => $categories->sum('children_count'),
    //         'categoriesSansOuvrages' => $categories->filter(fn($c) => $c->ouvrages_count === 0)->count(),
    //         'sousCategoriesSansOuvrages' => $categories->flatMap->children
    //             ->filter(fn($sc) => $sc->ouvrages_count === 0)->count(),
    //         'categorieLaPlusRiche' => $categories->concat($categories->flatMap->children)
    //             ->sortByDesc('ouvrages_count')
    //             ->first(),
    //         'derniersOuvrages' => $categories->flatMap->ouvrages
    //             ->concat($categories->flatMap->children->flatMap->ouvrages)
    //             ->sortByDesc('created_at')
    //             ->take(5)
    //             ->values()
    //     ];

    //     return view('classify_ouvrages', [
    //         'categories' => $categories,
    //         'stats' => $stats,
    //         'chartData' => $this->prepareChartData($categories)
    //     ]);
    // }

    /**
     * Prépare les données pour les graphiques
     */
    protected function prepareChartData($categories)
    {
        // Données pour le graphique de répartition
        $repartition = [
            'labels' => $categories->pluck('nom'),
            'data' => $categories->pluck('ouvrages_count'),
            'colors' => $this->generateColors($categories->count())
        ];

        // Données pour le graphique des sous-catégories
        $sousCategoriesData = [];
        foreach ($categories as $category) {
            if ($category->children->isNotEmpty()) {
                $sousCategoriesData[] = [
                    'name' => $category->nom,
                    'children' => $category->children->map(fn($child) => [
                        'name' => $child->nom,
                        'value' => $child->ouvrages_count
                    ])
                ];
            }
        }

        return [
            'repartition' => $repartition,
            'sousCategories' => $sousCategoriesData
        ];
    }

    /**
     * Génère des couleurs pour les graphiques
     */
    protected function generateColors($count)
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = '#' . substr(md5(rand()), 0, 6);
        }
        return $colors;
    }
}
