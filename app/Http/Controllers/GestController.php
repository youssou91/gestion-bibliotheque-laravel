<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GestController extends Controller
{
    public function gererCatalogue()
    {
        return view('gerer_catalogue');
    }

    public function gererStock()
    {
        return view('gerer_stock');
    }

    public function suivreVentes()
    {
        return view('suivre_ventes');
    }
}