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

    // Ajoutez ceci pour convertir automatiquement les dates en objets Carbon
    protected $dates = [
        'date_emprunt',
        'date_retour',
        'date_effective_retour',
        'created_at',
        'updated_at'
    ];

    // Ou pour Laravel 8+ (méthode recommandée)
    protected $casts = [
        'date_emprunt' => 'datetime',
        'date_retour' => 'datetime',
        'date_effective_retour' => 'datetime'
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

    public function getAmendeCouranteAttribute()
    {
        if ($this->statut !== 'en_retard') {
            return 0;
        }

        $jours = now()->diffInDays($this->date_retour);
        return $jours * 1.50;
    }

    public function amende()
    {
        return $this->hasOne(Amende::class);
    }
}
