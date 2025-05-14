<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
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
}
