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
        'transaction_id',
        'date_paiement',
        'mode_paiement',
        'reference_paiement',
        'donnees_paiement'
    ];
    
    protected $dates = [
        'date_paiement',
        'created_at',
        'updated_at',
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
    
    /**
     * Accesseur pour la propriété est_payee
     *
     * @return bool
     */
    public function getEstPayeeAttribute()
    {
        return $this->statut === 'payée';
    }
}
