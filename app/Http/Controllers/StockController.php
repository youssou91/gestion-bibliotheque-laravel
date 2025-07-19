<?php

namespace App\Http\Controllers;

use App\Models\Ouvrages;
use App\Models\Stocks;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Affiche la liste des stocks avec pagination et filtrage.
     */
    public function index(Request $request)
    {
        $query = Stocks::with('ouvrage');
        
        // Filtrage côté serveur
        if($request->has('statut')) {
            switch($request->statut) {
                case 'En stock':
                    $query->where('quantite', '>', 5);
                    break;
                case 'Stock faible':
                    $query->whereBetween('quantite', [1, 5]);
                    break;
                case 'Rupture':
                    $query->where('quantite', 0);
                    break;
            }
        }

        $stocks = $query->orderBy('quantite', 'asc')->paginate(10);

        // Calcul des statistiques
        $stats = [
            'total' => Stocks::count(),
            'en_stock' => Stocks::where('quantite', '>', 5)->count(),
            'stock_faible' => Stocks::whereBetween('quantite', [1, 5])->count(),
            'rupture' => Stocks::where('quantite', 0)->count(),
            'total_quantite' => Stocks::sum('quantite')
        ];

        // Récupérer tous les ouvrages pour le modal d'édition
        $ouvrages = Ouvrages::all();

        return view('gerer_stock', [
            'stocks' => $stocks,
            'stats' => $stats,
            'totalStock' => $stats['total_quantite'],
            'current_filter' => $request->statut ?? 'all',
            'ouvrages' => $ouvrages
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'quantite' => 'required|integer|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
        ]);

        // Détermination automatique du statut
        $validated['statut'] = $this->determinerStatut($validated['quantite']);

        Stocks::create($validated);
        return redirect()->route('getion.stocks')
                        ->with('success', 'Stock ajouté avec succès!');

        // return redirect()->route('gerer_stock');
                        // ->with('success', 'Stock ajouté avec succès!');
    }

    public function update(Request $request, $id)
    {
        $stock = Stocks::findOrFail($id);

        $validated = $request->validate([
            'quantite' => 'required|integer|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
        ]);

        // Mise à jour automatique du statut
        $validated['statut'] = $this->determinerStatut($validated['quantite']);

        $stock->update($validated);

        return redirect()->route('gerer_stockx')
                        ->with('success', 'Stock mis à jour avec succès!');
    }

    // Méthode pour déterminer le statut
    protected function determinerStatut($quantite)
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
  
   
}
