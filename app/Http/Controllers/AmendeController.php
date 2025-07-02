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
    public function index()
    {
        // Récupération de toutes les amendes avec les relations
        $amendes = Amende::with(['utilisateur', 'ouvrage', 'emprunt'])
            ->latest()
            ->get();

        // Calcul des statistiques
        $stats = [
            'total' => $amendes->count(),
            'impayees' => $amendes->where('est_payee', false)->count(),
            'payees' => $amendes->where('est_payee', true)->count(),
            'montant_total' => $amendes->sum('montant'),
            'montant_impaye' => $amendes->where('est_payee', false)->sum('montant'),
            'montant_paye' => $amendes->where('est_payee', true)->sum('montant')
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
