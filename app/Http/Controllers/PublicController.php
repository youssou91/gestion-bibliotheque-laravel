<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Ouvrages;

class PublicController extends Controller
{
    public function home()
    {
        $categories = Categories::all();
        return view('frontOffice.home', compact('categories'));
    }

    public function produits(Request $request)
    {
        $query = Ouvrages::query();

        if ($request->filled('search')) {
            $query->where('titre', 'like', '%'.$request->search.'%')
                  ->orWhere('auteur', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->where('categorie_id', $request->category);
        }

        $ouvrages = $query->paginate(10);
        $categories = Categories::all();

        return view('frontOffice.produits', compact('ouvrages', 'categories'));
    }

    public function details($id)
    {
        $ouvrage = Ouvrages::findOrFail($id);
        return view('frontOffice.details', compact('ouvrage'));
    }

    public function login()
    {
        return view('frontOffice.login');
    }

    public function register()
    {
        return view('frontOffice.register');
    }
}
