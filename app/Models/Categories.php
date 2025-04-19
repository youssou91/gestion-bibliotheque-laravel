<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'nom',
        'description',
        'parent_id',    // on ajoute parent_id au fillable
    ];

    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * Un enfant appartient à une seule catégorie parente
     */
    public function parent()
    {
        return $this->belongsTo(Categories::class, 'parent_id');
    }

    /**
     * Une catégorie peut avoir plusieurs sous‑catégories
     */
    public function children()
    {
        return $this->hasMany(Categories::class, 'parent_id');
    }

    /**
     * Une catégorie (ou sous‑catégorie) a plusieurs ouvrages
     */
    public function ouvrages()
    {
        return $this->hasMany(Ouvrages::class, 'categorie_id');
    }
}
