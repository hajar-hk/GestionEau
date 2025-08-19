<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementRG8 extends Model
{
    use HasFactory;

    protected $table = 'paiements_rg8';

    protected $fillable = [
        'numero_rg8',
        'numero_recu',
        'methode_reglement',
        'montant_paye',
        'date_paiement',
        'bordereau_rg12_id',
        'user_id',
        'facture_id',
        'statut',
        'penalite_retard',
    ];


    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class); //Un PaiementRG8 appartient à (belongs to) une Facture
    }


    // PS: On peut aussi ajouter les autres relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
