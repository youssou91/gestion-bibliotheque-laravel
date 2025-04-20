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
            'description'=>'nullable|string',
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
        $parents = Categories::whereNull('parent_id')->where('id','<>',$id)->get();
        return view('ModifierCategorie', compact('cat','parents'));
    }

    //  Met à jour
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nom'        => 'required|string|max:100',
            'description'=> 'nullable|string',
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

    //  Affiche les ouvrages d'une catégorie
    public function classifyOuvrages()
    {
        return view('classify_ouvrages');
    }

}
