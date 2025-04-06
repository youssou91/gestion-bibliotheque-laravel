<?php

namespace App\Http\Controllers;

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

}
