<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amende extends Model
{
    protected $fillable = [
        'utilisateur_id',
        'ouvrage_id',
        'emprunt_id',
        'montant',
        'statut',
        'motif',
    ];

    

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateurs::class);
    }

    public function ouvrage()
    {
        return $this->belongsTo(Ouvrages::class);
    }

    public function emprunt()
    {
        return $this->belongsTo(Emprunt::class);
    }
}
