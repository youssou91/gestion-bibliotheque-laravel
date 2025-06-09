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
    /**
     * Relation vers le modèle Stocks
     */
    public function stock()
    {
        return $this->hasOne(Stocks::class, 'ouvrage_id');
    }
    /**
     * Relation vers le modèle Commentaires
     */
    public function commentaires()
    {
        // return $this->hasMany(Commentaires::class, 'ouvrage_id');
        return $this->hasMany(Commentaires::class, 'ouvrage_id')->with('utilisateur')->latest();
    }
    /**
     * Relation vers le modèle Emprunts
     */
    // public function emprunts()
    // {
    //     return $this->hasMany(Emprunts::class, 'ouvrage_id');
    // }
    /**
     * Relation vers le modèle Utilisateurs
     */
    public function utilisateurs()
    {
        return $this->belongsToMany(Utilisateurs::class, 'emprunts', 'ouvrage_id', 'utilisateur_id');
    }
    /**
     * Relation vers le modèle Favoris
     */
    public function favoris()
    {
        return $this->belongsToMany(Utilisateurs::class, 'favoris', 'ouvrage_id', 'utilisateur_id');
    }
    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategorie($query, $categorieId)
    {
        if ($categorieId) {
            return $query->where('categorie_id', $categorieId);
        }
        return $query;
    }
    public function favori()
    {
        return $this->hasMany(Favori::class);
    }
}
