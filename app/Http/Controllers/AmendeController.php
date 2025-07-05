<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Amende;
use App\Models\Utilisateurs;
use App\Models\Ouvrages;
use App\Models\Emprunt;
use Illuminate\Http\Request;

class AmendeController extends Controller
{
     public function index(Request $request)
    {
        // Requête de base avec les relations
        $query = Amende::with(['utilisateur', 'ouvrage', 'emprunt'])
                    ->latest();
        
        // Filtres possibles
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('utilisateur', function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%");
            })->orWhereHas('ouvrage', function($q) use ($search) {
                $q->where('titre', 'like', "%$search%");
            });
        }

        // Pagination avec 20 éléments par page
        $amendes = $query->paginate(20)
                    ->appends($request->query());

        // Calcul des statistiques (optimisé)
        $stats = [
            'total' => Amende::count(),
            'impayees' => Amende::where('statut', 'impayee')->count(),
            'payees' => Amende::where('statut', 'payee')->count(),
            'montant_total' => Amende::sum('montant'),
            'montant_impaye' => Amende::where('statut', 'impayee')->sum('montant'),
            'montant_paye' => Amende::where('statut', 'payee')->sum('montant')
        ];

        return view('amendes', compact('amendes', 'stats'));
    }

    public function create()
    {
        $utilisateurs = Utilisateurs::all();
        $ouvrages = Ouvrages::all();
        $emprunts = Emprunt::all();

        return view('amendes', compact('utilisateurs', 'ouvrages', 'emprunts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'emprunt_id' => 'nullable|exists:emprunts,id',
            'montant' => 'required|numeric|min:0',
            'motif' => 'nullable|string',
        ]);

        Amende::create([
            'utilisateur_id' => $request->utilisateur_id,
            'ouvrage_id' => $request->ouvrage_id,
            'emprunt_id' => $request->emprunt_id,
            'montant' => $request->montant,
            'motif' => $request->motif,
            'statut' => 'impayee'
        ]);

        return redirect()->route('admin.amendes')->with('success', 'Amende ajoutée avec succès.');
    }

    public function edit(Amende $amende)
    {
        $utilisateurs = Utilisateurs::all();
        $ouvrages = Ouvrages::all();
        $emprunts = Emprunt::all();

        return view('admin.amendes', compact('amende', 'utilisateurs', 'ouvrages', 'emprunts'));
    }

    public function update(Request $request, Amende $amende)
    {
        $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'emprunt_id' => 'nullable|exists:emprunts,id',
            'montant' => 'required|numeric|min:0',
            'motif' => 'nullable|string',
            // 'est_payee' => 'required|boolean',
        ]);

        $amende->update($request->all());

        return redirect()->route('admin.amendes')->with('success', 'Amende mise à jour.');
    }

    public function destroy(Amende $amende)
    {
        $amende->delete();
        return redirect()->route('admin.amendes')->with('success', 'Amende supprimée.');
    }
}
