<?php

namespace App\Http\Controllers;

use App\Models\Ouvrages;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function getLivres(){
        // return Ouvrages::all(); //donnees retournees en json
        $livres = Ouvrages::all(); 
        return view('Edit_descriptions', compact('livres'));
    }
    // public function editDescriptions()
    // {
    //     return view('edit_descriptions');
    // }

    public function classifyOuvrages()
    {
        return view('classify_ouvrages');
    }

    public function validateComments()
    {
        return view('validate_comments');
    }
}