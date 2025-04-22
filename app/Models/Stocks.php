<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    //id	ouvrage_id	quantite	prix_achat	prix_vente	statut	created_at	updated_at	
    protected $table = 'stocks';
    protected $fillable = [
        'ouvrage_id',
        'quantite',
        'prix_achat',
        'prix_vente',
        'statut'
    ];
    /**
     * Un stock appartient à un ouvrage.
     */
    public function ouvrage()
    {
        return $this->belongsTo(Ouvrages::class, 'ouvrage_id');
    }
    /**
     * Un stock appartient à un utilisateur.
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateur_id');
    }
}
