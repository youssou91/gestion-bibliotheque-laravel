<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ouvrages extends Model
{
    protected $table = 'ouvrages'; 
    protected $fillable = [
        'titre',
        'auteur',
        'annee_publication',
        'niveau',
        'photo',
        'description',
        'categorie_id',
    ]; // Champs remplissables
    protected $primaryKey = 'id'; // Clé primaire
    public $timestamps = true; 

    /**
     * Relation vers le modèle Categories
     */
    public function categorie()
    {
        return $this->belongsTo(Categories::class, 'categorie_id');
    }
}
