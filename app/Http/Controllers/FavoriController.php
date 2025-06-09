<?php

namespace App\Http\Controllers;

use App\Models\Ouvrage;
use App\Models\Favori;
use App\Models\Ouvrages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    public function index()
    {
        
        $favoris = Auth::user()->favoris()->with('ouvrage.commentaires.utilisateur')->paginate(6);


        return view('frontOffice.favoris', compact('favoris'));
    }

    public function ajouter(Request $request, $ouvrage_id)
    {
        $user = Auth::user();

        if (!$user) {
            return back()->with('error', 'Vous devez être connecté pour ajouter aux favoris');
        }

        $ouvrage = Ouvrages::find($ouvrage_id);
        if (!$ouvrage) {
            return back()->with('error', 'Ouvrage introuvable');
        }

        $existe = Favori::where('utilisateur_id', $user->id)
            ->where('ouvrage_id', $ouvrage_id)
            ->exists();

        if ($existe) {
            return back()->with('info', 'Cet ouvrage est déjà dans vos favoris');
        }

        Favori::create([
            'utilisateur_id' => $user->id,
            'ouvrage_id' => $ouvrage_id,
        ]);

        return back()->with('success', 'Ouvrage ajouté aux favoris');
    }

    public function retirer($ouvrage_id)
    {
        $user = Auth::user();
        // $favoris = Auth::user()->favoris()->paginate(6); // ou le nombre d’éléments par page que tu veux


        if (!$user) {
            return back()->with('error', 'Vous devez être connecté pour retirer des favoris');
        }

        $favori = Favori::where('utilisateur_id', $user->id)
            ->where('ouvrage_id', $ouvrage_id)
            ->first();

        if (!$favori) {
            return back()->with('error', 'Favori introuvable');
        }

        $favori->delete();

        return back()->with('success', 'Ouvrage retiré des favoris');
    }
}
