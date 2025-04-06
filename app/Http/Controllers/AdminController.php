<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function maintenirSite()
    {
        return view('maintenir_site');
    }

    public function gererUtilisateurs()
    {
        return view('gerer_utilisateurs');
    }
}