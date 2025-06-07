<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Utilisateurs extends Authenticatable
{
    // Constantes pour les rôles
    const ROLE_CLIENT = 'client';
    const ROLE_EDITEUR = 'editeur';
    const ROLE_GESTIONNAIRE = 'gestionnaire';
    const ROLE_ADMIN = 'administrateur';

    use HasFactory, Notifiable;

    // Spécifie la bonne table
    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'adresse',
        'telephone',
        'photo',
        'role',
        'email',
        'password',
        'statut',
        'photo',
        'last_login_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Méthodes pour vérifier les rôles
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isEditeur()
    {
        return $this->role === self::ROLE_EDITEUR;
    }

    public function isGestionnaire()
    {
        return $this->role === self::ROLE_GESTIONNAIRE;
    }

    public function isClient()
    {
        return $this->role === self::ROLE_CLIENT;
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // Ajouter dans le modèle Utilisateurs
    protected $dates = [
        'last_login_at',
        'created_at',
        'updated_at'
    ];

    public function updateLastLogin()
    {
        $this->update([
            'last_login_at' => now()
        ]);
    }
    // Ajouter cette méthode pour l'URL complète
    // public function getPhotoUrlAttribute()
    // {
    //     return $this->photo
    //         ? asset('storage/' . $this->photo)
    //         : asset('images/default-avatar.png');
    // }
    public function favoris()
    {
        return $this->belongsToMany(Ouvrages::class, 'favoris', 'utilisateur_id', 'ouvrage_id')
            ->withTimestamps();
    }
    public function emprunts()
    {
        return $this->hasMany(Emprunt::class, 'utilisateur_id');
    }
    public function commentaires()
    {
        return $this->hasMany(Commentaires::class, 'utilisateur_id');
    }

    // public function updateProfile(Request $request)
    // {
    //     $request->validate([
    //         'nom' => 'required|string|max:255',
    //         'prenom' => 'required|string|max:255',
    //         'adresse' => 'nullable|string|max:255',
    //         'telephone' => 'nullable|string|max:20',
    //         'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     $user = Auth::user();
    //     $user->nom = $request->input('nom');
    //     $user->prenom = $request->input('prenom');
    //     $user->adresse = $request->input('adresse');
    //     $user->telephone = $request->input('telephone');

    //     if ($request->hasFile('photo')) {
    //         // Supprimer l'ancienne photo si elle existe
    //         if ($user->photo && Storage::exists($user->photo)) {
    //             Storage::delete($user->photo);
    //         }
    //         // Enregistrer la nouvelle photo
    //         $path = $request->file('photo')->store('photos', 'public');
    //         $user->photo = $path;
    //     }

    //     $user->save();

    //     return redirect()->route('profile')->with('success', 'Profil mis à jour avec succès.');
    // }
}