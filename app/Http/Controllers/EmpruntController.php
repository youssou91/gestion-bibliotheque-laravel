<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Ouvrages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpruntController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'livre_id' => 'required|exists:ouvrages,id',
        ]);
        // Vérifier si l'utilisateur a déjà un emprunt en cours pour ce livre
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

        // Optionnel : mettre à jour le statut du livre dans le stock
        $livre = Ouvrages::find($request->livre_id);
        if ($livre->stock && $livre->stock->quantite > 0) {
            $livre->stock->decrement('quantite');
        }

        return redirect()->back()->with('success', 'Livre emprunté avec succès.');
    }
    // retour
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

        // Optionnel : ré-incrémenter le stock
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
    // le nombre de livres empruntés par l'utilisateur
    public function nbEmprunts()
    {
        $countEmprunts = Emprunt::where('utilisateur_id', auth()->id())
            ->where('statut', 'en_cours')
            ->count();
        dd($countEmprunts);
        return $countEmprunts;
        // return view('frontOffice.profile', compact('countEmprunts'));
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
        // $favoris = Auth::user()->favoris()->with('stock')->get();
        $favoris = Auth::user();
        // $favoris = Auth::user()->favoris()
        //     ->with(['stock', 'auteur', 'categorie'])
        //     ->orderBy('created_at', 'desc')
        //     ->get();
        // dd($favoris);
        return view('frontOffice.favoris', compact('favoris'));
    }

    // public function toggle($id)
    // {
    //     $user = Auth::user();
    //     $livre = Ouvrages::findOrFail($id);

    //     if($user->favoris()->where('ouvrage_id', $id)->exists()) {
    //         $user->favoris()->detach($id);
    //         return back()->with('success', 'Livre retiré des favoris.');
    //     } else {
    //         $user->favoris()->attach($id);
    //         return back()->with('success', 'Livre ajouté aux favoris.');
    //     }
    // }
    /*
    public function historique()
    {
        $emprunts = Emprunt::where('utilisateur_id', auth()->id())
            ->with(['ouvrage', 'ouvrage.stock'])
            ->orderBy('date_emprunt', 'desc')
            ->get();

        return view('emprunts.historique', compact('emprunts'));
    }
    public function show($id)
    {
        $emprunt = Emprunt::with(['ouvrage', 'ouvrage.stock'])->findOrFail($id);
        return view('emprunts.show', compact('emprunt'));
    }
    
    public function destroy($id)
    {
        $emprunt = Emprunt::findOrFail($id);
        $emprunt->delete();

        return redirect()->route('emprunts.index')->with('success', 'Emprunt supprimé avec succès.');
    }
    */
}
