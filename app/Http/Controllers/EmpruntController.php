<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Ouvrages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpruntController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'livre_id' => 'required|exists:ouvrages,id',
        ]);
        $empruntEnCours = Emprunt::where('utilisateur_id', auth()->id())
            ->where('ouvrage_id', $request->livre_id)
            ->where('statut', 'en_cours')
            ->exists();
        if ($empruntEnCours) {
            return redirect()->back()->with('error', 'Vous avez déjà un emprunt en cours pour ce livre.');
        }
        $dateRetour = now()->addDays(14);

        Emprunt::create([
            'utilisateur_id' => auth()->id(),
            'ouvrage_id' => $request->livre_id,
            'date_emprunt' => now(),
            'date_retour' => $dateRetour,
            'statut' => 'en_cours',
        ]);

        $livre = Ouvrages::find($request->livre_id);
        if ($livre->stock && $livre->stock->quantite > 0) {
            $livre->stock->decrement('quantite');
        }

        return redirect()->back()->with('success', 'Livre emprunté avec succès.');
    }
    public function retour($id)
    {
        $emprunt = Emprunt::findOrFail($id);
        $today = now();
        $retard = $today->greaterThan($emprunt->date_retour);
        $amende = 0;
        if ($retard) {
            $jours = $today->diffInDays($emprunt->date_retour);
            $amende = $jours * 1.50;
        }
        $emprunt->update([
            'date_effective_retour' => $today,
            'statut' => 'retourne',
            'amende' => $amende
        ]);
        if ($emprunt->ouvrage->stock) {
            $emprunt->ouvrage->stock->increment('quantite');
        }
        return back()->with('success', 'Livre retourné. Amende : $' . number_format($amende, 2));
    }
    public function index()
    {
        $emprunts = Emprunt::with(['ouvrage', 'utilisateur'])
            ->orderBy('date_emprunt', 'desc')
            ->get();

        return view('emprunts.index', compact('emprunts'));
    }
    public function nbEmprunts()
    {
        $countEmprunts = Emprunt::where('utilisateur_id', auth()->id())
            ->where('statut', 'en_cours')
            ->count();
        dd($countEmprunts);
        return $countEmprunts;
    }

    public function mesEmprunts()
    {
        $utilisateurId = auth()->id();

        $empruntsEnCours = Emprunt::with('ouvrage')
            ->where('utilisateur_id', $utilisateurId)
            ->where('statut', 'en_cours')
            ->orderBy('date_emprunt', 'desc')
            ->get();

        $empruntsHistorique = Emprunt::with('ouvrage')
            ->where('utilisateur_id', $utilisateurId)
            ->where('statut', '!=', 'en_cours')
            ->orderBy('date_retour', 'desc')
            ->paginate(10);

        return view('frontOffice.emprunts', compact('empruntsEnCours', 'empruntsHistorique'));
    }

    public function favoris()
    {
        $favoris = Auth::user();
        return view('frontOffice.favoris', compact('favoris'));
    }  
}