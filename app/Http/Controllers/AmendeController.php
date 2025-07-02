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
        $amendes = Amende::with(['utilisateur', 'ouvrage', 'emprunt'])->latest()->get();
        return view('admin.amendes.index', compact('amendes'));
    }

    public function create()
    {
        $utilisateurs = Utilisateurs::all();
        $ouvrages = Ouvrages::all();
        $emprunts = Emprunt::all();

        return view('admin.amendes.create', compact('utilisateurs', 'ouvrages', 'emprunts'));
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
            'statut' =>'impayee'
        ]);

        return redirect()->route('admin.amendes.index')->with('success', 'Amende ajoutée avec succès.');
    }

    public function edit(Amende $amende)
    {
        $utilisateurs = Utilisateurs::all();
        $ouvrages = Ouvrages::all();
        $emprunts = Emprunt::all();

        return view('admin.amendes.edit', compact('amende', 'utilisateurs', 'ouvrages', 'emprunts'));
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

        return redirect()->route('admin.amendes.index')->with('success', 'Amende mise à jour.');
    }

    public function destroy(Amende $amende)
    {
        $amende->delete();
        return redirect()->route('admin.amendes.index')->with('success', 'Amende supprimée.');
    }
}
