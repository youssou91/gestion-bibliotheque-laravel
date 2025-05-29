<?php

namespace App\Http\Controllers;

use App\Models\Utilisateurs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UtilisateurController extends Controller
{
    /**
     * Middleware de protection
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Affiche le profil en fonction du rôle
    */
    public function profileByRole()
    {
        $user = Auth::user();

        $donneesProfil = [
            'nom_complet' => $user->prenom . ' ' . $user->nom,
            'email' => $user->email,
            'adresse' => $user->adresse ?? 'Adresse non renseignée',
            'telephone' => $user->telephone ?? 'Téléphone non renseigné',
            'role' => $user->role,
            'statut' => $user->statut ?? 'Actif',
            'photo' => $user->photo ?? 'default-avatar.png',
            'date_inscription' => $user->created_at->format('d/m/Y'),
            'date_derniere_connexion' => optional($user->last_login_at)->format('d/m/Y H:i'),
        ];

        return match ($user->role) {
            'administrateur', 'admin' => view('admin.profile', compact('donneesProfil')),
            'client'                 => view('frontOffice.profile', compact('donneesProfil')),
            'gestionnaire'           => view('gestionnaire.profile', compact('donneesProfil')),
            default                  => abort(403, 'Accès non autorisé.')
        };
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si existe
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }

            // Stocker la nouvelle photo
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }

        // $user->save();

        return redirect()->back()->with('success', 'Profil mis à jour !');
    }
}
