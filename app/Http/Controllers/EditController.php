<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Ouvrages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditController extends Controller
{
    public function getLivres()
    {
        $livres = Ouvrages::with('categorie') 
                         ->orderBy('titre', 'asc')
                         ->paginate(10); 

        // Calcul des statistiques
        $stats = [
            'total' => Ouvrages::count(),
            'disponibles' => Ouvrages::where('statut', 'disponible')->count(),
            'reserves' => Ouvrages::where('statut', 'réservé')->count(),
            'empruntes' => Ouvrages::where('statut', 'emprunté')->count(),
        ];

        return view('GestCatalogue', [
            'livres' => $livres,
            'stats' => $stats
        ]);
    }
    public function getCategories()
    {
        $categories = Categories::all();
        return view('AjoutOuvrages', compact('categories'));
    }
    public function store(Request $request)
    {
        // Valider les données requises
        $request->validate([
            'titre' => 'required',
            'auteur' => 'required',
            'editeur' => 'required',
            'isbn' => 'required|unique:ouvrages',
            'prix' => 'required|numeric|min:0',
            'annee_publication' => 'required',
            'niveau' => 'required',
            'categorie_id' => 'required|exists:categories,id',
            'description' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        $ouvrage = new Ouvrages();
        $ouvrage->titre = $request->titre;
        $ouvrage->auteur = $request->auteur;
        $ouvrage->editeur = $request->editeur;
        $ouvrage->isbn = $request->isbn;
        $ouvrage->prix = $request->prix;
        $ouvrage->annee_publication = $request->annee_publication;
        $ouvrage->niveau = $request->niveau;
        $ouvrage->categorie_id = $request->categorie_id;
        $ouvrage->description = $request->description;

        // Traiter la photo
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_nom = time() . $photo->getClientOriginalName();
            $photo->move(public_path("assets/img"), $photo_nom);
            $ouvrage->photo = $photo_nom;
        }

        $ouvrage->save();
        return redirect()->back()->with('success', 'Ouvrage ajouté avec succès !');
    }
    public function destroy(string $id)
    {
        // Récupérer l'ouvrage à supprimer
        $ouvrage = Ouvrages::findOrFail($id);
        // Supprimer l'image du disque
        $img_path = public_path('assets/img/' . $ouvrage->photo);
        if (file_exists($img_path)) {
            unlink($img_path);
        }
        // Supprimer l'ouvrage de la base de données
        $ouvrage->delete();
        return redirect()->back()->with('success', 'Ouvrage supprimé avec succès !');
    }
    public function edit(string $id)
    {
        // Récupérer l'ouvrage à modifier
        $ouvrage = Ouvrages::findOrFail($id);
        // Récupérer toutes les catégories
        $categories = Categories::all();
        // Passer l'ouvrage et les catégories à la vue
        return view('ModifierOuvrages', compact('ouvrage', 'categories'));
    }
    public function update(Request $request, string $id)
    {
        $ouvrage = Ouvrages::findOrFail($id);

        $data = $request->only(['titre', 'date_publication', 'niveau', 'categorie_id', 'description']);

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($ouvrage->photo) {
                $oldPath = public_path('assets/img/' . $ouvrage->photo);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Stocker la nouvelle photo
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('assets/img'), $filename);
            $data['photo'] = $filename;
        }

        $ouvrage->update($data);

        return back()->with('success', 'Ouvrage modifié avec succès !');
    }
    // public function update(Request $request, string $id)
    // {
    //     // Récupérer l'ouvrage à modifier
    //     $ouvrage = Ouvrages::findOrFail($id);
    //     // Mettre à jour les champs de l'ouvrage
    //     $ouvrage->titre = $request->titre;
    //     $ouvrage->date_publication = $request->date_publication;
    //     $ouvrage->niveau = $request->niveau;
    //     $ouvrage->categorie_id = $request->categorie_id;
    //     $ouvrage->description = $request->description;
    //     // Traiter la photo
    //     if ($request->hasFile('photo')) {
    //         // Supprimer l'ancienne image du disque
    //         $img_path = public_path('assets/img/' . $ouvrage->photo);
    //         if (file_exists($img_path)) {
    //             unlink($img_path);
    //         }
    //         // Enregistrer la nouvelle image
    //         $photo = $request->file('photo');
    //         $photo_nom = time() . $photo->getClientOriginalName();
    //         $photo->move(public_path("assets/img"), $photo_nom);
    //         $ouvrage->photo = $photo_nom;
    //     }
    //     // Enregistrer les modifications
    //     $ouvrage->save();
    //     return redirect()->back()->with('success', 'Ouvrage modifié avec succès !');
    // }

    public function destroyOuvrage($id)
    {
        // Récupérer l'ouvrage à supprimer
        $ouvrage = Ouvrages::findOrFail($id);
        // Supprimer l'image du disque
        $img_path = public_path('assets/img/' . $ouvrage->photo);
        if (file_exists($img_path)) {
            unlink($img_path);
        }
        // Supprimer l'ouvrage de la base de données
        $ouvrage->delete();
        // Rediriger vers la page des livres avec un message de succès
        return redirect()->back()->with('success', 'Ouvrage supprimé avec succès !');
    }
    public function editOuvrage($id)
    {
        // Récupérer l'ouvrage à modifier
        $ouvrage = Ouvrages::findOrFail($id);
        return view('modifier_ouvrage', compact('ouvrage'));
    }
    public function updateOuvrage(Request $request, $id)
    {
        // Récupérer l'ouvrage à modifier
        $ouvrage = Ouvrages::findOrFail($id);
        // Mettre à jour les champs de l'ouvrage
        $ouvrage->titre = $request->titre;
        $ouvrage->auteur = $request->auteur;
        $ouvrage->date_publication = $request->date_publication;
        $ouvrage->niveau = $request->niveau;
        // Traiter la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne image du disque
            $img_path = public_path('assets/img/' . $ouvrage->photo);
            if (file_exists($img_path)) {
                unlink($img_path);
            }
            // Enregistrer la nouvelle image
            $photo = $request->file('photo');
            $photo_nom = time() . $photo->getClientOriginalName();
            $photo->move(public_path("assets/img"), $photo_nom);
            $ouvrage->photo = $photo_nom;
        }
        // Enregistrer les modifications
        $ouvrage->save();
        return redirect()->back()->with('success', 'Ouvrage modifié avec succès !');
    }

    public function apiShow($id)
    {
        $livre = Ouvrages::with('categorie')->findOrFail($id);
        return response()->json($livre);
    }
}
