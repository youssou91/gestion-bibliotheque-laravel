<?php

namespace App\Http\Controllers;

use App\Models\Commentaires;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    // Affichage de vue a partir du controller
    public function index()
    {
        $prenom = "Yussuf";
        $nom = "Gning";
        $data = [
            'programme'=>'Genie Logiciel',
            'nationalite'=>'Senegal'
        ];
        return view('TestVueController', compact('prenom', 'nom'), ['data'=>$data]);

    }
    public function commenter(Request $request, $id)
{
    $request->validate([
        'commentaire' => 'required|string|max:1000',
        'note' => 'required|integer|between:1,5',
    ]);

    // Enregistrez le commentaire (à adapter selon votre modèle)
    Commentaires::create([
        'livre_id' => $id,
        'utilisateur_id' => auth()->id(),
        'contenu' => $request->commentaire,
        'note' => $request->note,
    ]);

    return redirect()->back()->with('success', 'Merci pour votre commentaire !');
}


}
