<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditController extends Controller
{
    public function editDescriptions()
    {
        return view('edit_descriptions');
    }

    public function classifyOuvrages()
    {
        return view('classify_ouvrages');
    }

    public function validateComments()
    {
        return view('validate_comments');
    }
}