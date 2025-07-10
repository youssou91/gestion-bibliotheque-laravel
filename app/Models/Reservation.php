<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $casts = [
        'date_reservation' => 'datetime',
    ];

    protected $fillable = [
        'ouvrage_id',
        'utilisateur_id',
        'date_reservation',
        'statut'
    ];

    public function ouvrage()
    {
        return $this->belongsTo(Ouvrages::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateurs::class);
    }

    // Relations
    public function scopeConfirmees($query)
    {
        return $query->where('statut', 'validee');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAnnulees($query)
    {
        return $query->where('statut', 'annulee');
    }

    public function emprunt()
    {
        return $this->hasOne(Emprunt::class, 'ouvrage_id', 'ouvrage_id')
            ->where('utilisateur_id', $this->utilisateur_id)
            ->where('statut', 'en_cours');
    }
}
