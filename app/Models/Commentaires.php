<?php
// app/Models/Commentaire.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Utilisateurs;
use App\Models\Ouvrages;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commentaires extends Model
{
    use HasFactory;

    /**
     * Nom de la table
     */
    protected $table = 'commentaires';

    /**
     * Champs autorisés à la mise à jour
     */
    protected $fillable = [
        'contenu',
        'utilisateur_id',
        'ouvrage_id',
        'note',
        'statut',
    ];

    /**
     * Un commentaire appartient à un utilisateur.
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateur_id');
    }

    /**
     * Un commentaire appartient à un ouvrage.
     */
    public function ouvrage()
    {
        return $this->belongsTo(Ouvrages::class, 'ouvrage_id');
    }
}



