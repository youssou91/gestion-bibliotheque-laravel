<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ligne_ventes extends Model
{
    
    //id	vente_id	ouvrage_id	utilisateur_id	quantite	prix_unitaire	created_at	updated_at
    protected $table = 'ligne_ventes'; // Nom de la table associée
    protected $primaryKey = 'id'; //
    protected $fillable = [
        'vente_id',
        'ouvrage_id',
        'utilisateur_id',
        'quantite',
        'prix_unitaire',
    ];
    public function vente()
    {
        return $this->belongsTo(Ventes::class, 'vente_id');
    }
    public function ouvrage()
    {
        return $this->belongsTo(Ouvrages::class, 'ouvrage_id');
    }
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateurs::class, 'utilisateur_id');
    }
    
    // Scopes pour la recherche et le filtrage
    public function scopeSearch($query, $search)
    {
        return $query->where('ouvrage_id', 'like', "%$search%")
            ->orWhere('vente_id', 'like', "%$search%")
            ->orWhere('utilisateur_id', 'like', "%$search%");
    }
    public function scopeFilterByDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }
    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
    public function scopeFilterByUser($query, $userId)
    {
        return $query->where('utilisateur_id', $userId);
    }
    public function scopeFilterByBook($query, $bookId)
    {
        return $query->where('ouvrage_id', $bookId);
    }
    public function scopeFilterByQuantity($query, $quantity)
    {
        return $query->where('quantite', $quantity);
    }   
}
