<?php

namespace App\Http\Controllers;

use App\Models\Ouvrages;
use App\Models\Stocks;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Affiche la liste des stocks.
     */
    public function index()
    {
        // Récupération des stocks avec la relation ouvrage et pagination
        $stocks = Stocks::with('ouvrage')
                      ->orderBy('quantite', 'asc')
                      ->paginate(10);

        // Calcul des statistiques en une seule requête
        $stats = [
            'total' => Stocks::count(),
            'en_stock' => Stocks::where('quantite', '>', 5)->count(),
            'stock_faible' => Stocks::whereBetween('quantite', [1, 5])->count(),
            'rupture' => Stocks::where('quantite', 0)->count(),
            'total_quantite' => Stocks::sum('quantite')
        ];

        return view('gerer_stock', [
            'stocks' => $stocks,
            'stats' => $stats,
            'totalStock' => $stats['total_quantite']
        ]);
    }

    // Méthode pour déterminer le statut (utile pour create/update)
    protected function determineStatut($quantite)
    {
        if ($quantite == 0) {
            return 'Rupture';
        } elseif ($quantite <= 5) {
            return 'Stock faible';
        } else {
            return 'En stock';
        }
    }

    /**
     * Formulaire de modification d'un stock.
     */
    public function edit(Stocks $stock)
    {
        return view('stocks.edit', compact('stock'));
    }

    /**
     * Met à jour un stock.
     */
    public function update(Request $request, Stocks $stock)
    {
        $data = $request->validate([
            'quantite'    => 'required|integer|min:0',
            'prix_achat'  => 'required|numeric|min:0',
            'prix_vente'  => 'required|numeric|min:0',
            'statut'      => 'required|string|in:En stock,Stock faible,Rupture',
        ]);

        $stock->update($data);

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Stock mis à jour.');
    }
    /**
     * Show stock.
     */
    // public function show(Stocks $stock)
    // {
    //     return $stock;
    // }

    public function show(Stocks $stock)
    {
        // Charge la relation pour le JSON
        $stock->load('ouvrage');
        return response()->json($stock);
    }

    /**
     * Supprime un stock.
     */
    public function destroy(Stocks $stock)
    {
        $stock->delete();

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Stock supprimé.');
    }
    /**
     * Affiche le formulaire de création d'un stock.
     */
    public function create()
    {
        // Récupérer tous les ouvrages pour le formulaire
        $ouvrages = \App\Models\Ouvrages::all();
        // Passer les ouvrages à la vue
        return view('AjoutStock', compact('ouvrages'));
    }
    /**
     * Enregistre un nouveau stock.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ouvrage_id'  => 'required|exists:ouvrages,id',
            'quantite'    => 'required|integer|min:0',
            'prix_achat'  => 'required|numeric|min:0',
            'prix_vente'  => 'required|numeric|min:0',
            'statut'      => 'required|string|in:En stock,Stock faible,Rupture',
        ]);

        Stocks::create($data);

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Stock créé.');
    }
}
