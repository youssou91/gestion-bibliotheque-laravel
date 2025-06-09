<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'ouvrage_id'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateur_id');
    }

    public function ouvrage()
    {
        return $this->belongsTo(Ouvrages::class, 'ouvrage_id');
    }
}