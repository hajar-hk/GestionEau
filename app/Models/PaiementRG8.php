<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}