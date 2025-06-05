<?php

namespace App\Http\Controllers;

use App\Models\Ouvrages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    public function index()
    {
        // $favoris = Auth::user()->favoris()->with('stock')->get();
        $favoris = [
            [
                'id' => 1,
                'titre' => 'Livre 1',
                'auteur' => 'Auteur 1',
                'annee_publication' => 2020,
                'niveau' => 'Facile',
                'photo' => 'photo1.jpg',
                'description' => 'Description du livre 1',
                'categorie_id' => 1,
            ],
            [
                'id' => 2,
                'titre' => 'Livre 2',
                'auteur' => 'Auteur 2',
                'annee_publication' => 2021,
                'niveau' => 'Moyen',
                'photo' => 'photo2.jpg',
                'description' => 'Description du livre 2',
                'categorie_id' => 2,
            ],
            [
                'id' => 3,
                'titre' => 'Livre 3',
                'auteur' => 'Auteur 3',
                'annee_publication' => 2022,
                'niveau' => 'Difficile',
                'photo' => 'photo3.jpg',
                'description' => 'Description du livre 3',
                'categorie_id' => 3,
            ],
        ];
        // $favoris = Auth::user();
        return view('frontOffice.favoris', compact('favoris'));
    }

    public function toggle(Request $request, $id)
    {
        // $user = Auth::user();
        // $ouvrage = Ouvrages::findOrFail($id);

        // if($user->favoris()->where('ouvrage_id', $id)->exists()) {
        //     $user->favoris()->detach($id);
        //     return back()->with('success', 'Ouvrage retiré des favoris.');
        // } else {
        //     $user->favoris()->attach($id);
        //     return back()->with('success', 'Ouvrage ajouté aux favoris.');
        // }
    }
}