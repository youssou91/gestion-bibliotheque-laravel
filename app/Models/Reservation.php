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
}
