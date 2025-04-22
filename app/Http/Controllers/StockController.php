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
        // Charge chaque stock avec son ouvrage
        $stocks = Stocks::with('ouvrage')->get();

        return view('gerer_stock', compact('stocks'));
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
