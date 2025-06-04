<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprunt extends Model
{
    use HasFactory;

    protected $table = 'emprunts';

    protected $fillable = [
        'utilisateur_id',
        'ouvrage_id',
        'date_emprunt',
        'date_retour',
        'date_effective_retour',
        'amende',
        'statut'
    ];

    /**
     * Relation : un emprunt appartient à un utilisateur.
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateur_id');
    }

    /**
     * Relation : un emprunt concerne un ouvrage.
     */
    public function ouvrage()
    {
        return $this->belongsTo(Ouvrages::class, 'ouvrage_id');
    }
}
