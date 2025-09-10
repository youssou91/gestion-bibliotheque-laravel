<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ouvrages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OuvrageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Ouvrages::with(['auteur', 'editeur', 'categorie', 'stock']);
        
        // Filtrage par catégorie
        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        
        // Filtrage par auteur
        if ($request->has('auteur_id')) {
            $query->where('auteur_id', $request->auteur_id);
        }
        
        // Filtrage par statut de disponibilité
        if ($request->has('disponible')) {
            $query->whereHas('stock', function($q) {
                $q->where('quantite', '>', 0);
            });
        }
        
        // Recherche par titre ou description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }
        
        // Tri
        $sortField = $request->input('sort_by', 'titre');
        $sortDirection = $request->input('sort_dir', 'asc');
        $query->orderBy($sortField, $sortDirection);
        
        // Pagination
        $perPage = $request->input('per_page', 15);
        $ouvrages = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $ouvrages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur_id' => 'required|exists:auteurs,id',
            'editeur_id' => 'required|exists:editeurs,id',
            'categorie_id' => 'required|exists:categories,id',
            'isbn' => 'required|string|unique:ouvrages,isbn',
            'annee_publication' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'nombre_pages' => 'required|integer|min:1',
            'langue' => 'required|string|size:2',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'quantite' => 'required|integer|min:0',
            'prix' => 'required|numeric|min:0',
        ]);
        
        // Gestion de l'image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/ouvrages');
            $validated['image_url'] = Storage::url($path);
        }
        
        // Création de l'ouvrage
        $ouvrage = Ouvrages::create($validated);
        
        // Création du stock
        $ouvrage->stock()->create([
            'quantite' => $validated['quantite'],
            'quantite_min' => 5,
            'prix' => $validated['prix']
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ouvrage créé avec succès',
            'data' => $ouvrage->load(['auteur', 'editeur', 'categorie', 'stock'])
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $ouvrage = Ouvrages::with(['auteur', 'editeur', 'categorie', 'stock', 'commentaires.utilisateur'])
            ->findOrFail($id);
            
        return response()->json([
            'success' => true,
            'data' => $ouvrage
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $ouvrage = Ouvrages::findOrFail($id);
        
        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'auteur_id' => 'sometimes|required|exists:auteurs,id',
            'editeur_id' => 'sometimes|required|exists:editeurs,id',
            'categorie_id' => 'sometimes|required|exists:categories,id',
            'isbn' => 'sometimes|required|string|unique:ouvrages,isbn,' . $ouvrage->id,
            'annee_publication' => 'sometimes|required|integer|min:1000|max:' . (date('Y') + 1),
            'nombre_pages' => 'sometimes|required|integer|min:1',
            'langue' => 'sometimes|required|string|size:2',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'quantite' => 'sometimes|required|integer|min:0',
            'prix' => 'sometimes|required|numeric|min:0',
        ]);
        
        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($ouvrage->image_url) {
                $oldImage = str_replace('/storage', 'public', $ouvrage->image_url);
                Storage::delete($oldImage);
            }
            
            $path = $request->file('image')->store('public/ouvrages');
            $validated['image_url'] = Storage::url($path);
        }
        
        // Mise à jour de l'ouvrage
        $ouvrage->update($validated);
        
        // Mise à jour du stock si nécessaire
        if (isset($validated['quantite']) || isset($validated['prix'])) {
            $stockData = [];
            if (isset($validated['quantite'])) {
                $stockData['quantite'] = $validated['quantite'];
            }
            if (isset($validated['prix'])) {
                $stockData['prix'] = $validated['prix'];
            }
            
            $ouvrage->stock()->updateOrCreate(
                ['ouvrage_id' => $ouvrage->id],
                $stockData
            );
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Ouvrage mis à jour avec succès',
            'data' => $ouvrage->load(['auteur', 'editeur', 'categorie', 'stock'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ouvrage = Ouvrages::findOrFail($id);
        
        // Vérifier s'il y a des emprunts en cours
        if ($ouvrage->emprunts()->whereIn('statut', ['en_cours', 'en_retard'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer cet ouvrage car il est actuellement emprunté.'
            ], 400);
        }
        
        // Supprimer l'image si elle existe
        if ($ouvrage->image_url) {
            $imagePath = str_replace('/storage', 'public', $ouvrage->image_url);
            Storage::delete($imagePath);
        }
        
        // Supprimer l'ouvrage
        $ouvrage->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Ouvrage supprimé avec succès'
        ]);
    }
    
    /**
     * Recherche avancée d'ouvrages
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = Ouvrages::with(['auteur', 'editeur', 'categorie', 'stock']);
        
        // Filtres de recherche
        if ($request->has('titre')) {
            $query->where('titre', 'like', '%' . $request->titre . '%');
        }
        
        if ($request->has('auteur')) {
            $query->whereHas('auteur', function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->auteur . '%')
                  ->orWhere('prenom', 'like', '%' . $request->auteur . '%');
            });
        }
        
        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        
        if ($request->has('annee_min')) {
            $query->where('annee_publication', '>=', $request->annee_min);
        }
        
        if ($request->has('annee_max')) {
            $query->where('annee_publication', '<=', $request->annee_max);
        }
        
        if ($request->has('disponible') && $request->disponible) {
            $query->whereHas('stock', function($q) {
                $q->where('quantite', '>', 0);
            });
        }
        
        // Tri
        $sortField = $request->input('sort_by', 'titre');
        $sortDirection = $request->input('sort_dir', 'asc');
        $query->orderBy($sortField, $sortDirection);
        
        // Pagination
        $perPage = $request->input('per_page', 15);
        $resultats = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $resultats
        ]);
    }
}
