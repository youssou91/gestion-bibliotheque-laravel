<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ventes extends Model
{
    //id	montant_total	created_at	updated_at	
    protected $table = 'ventes'; // Nom de la table associée
    protected $primaryKey = 'id'; //
    protected $fillable = [
        'montant_total',
    ];
    public function ligneventes()
    {
        return $this->hasMany(Ligneventes::class, 'vente_id');
    }
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateur_id');
    }
}
