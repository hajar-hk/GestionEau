<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BordereauRg12 extends Model
{
    protected $table = 'bordereaux_rg12';
    protected $fillable = [
        'numero_rg12',
        'montant_total',
        'date_creation',
        'declaration_id'
    ];
    public function paiements(): HasMany
    {
        return $this->hasMany(PaiementRG8::class, 'bordereau_rg12_id'); //Un BordereauRg12 a plusieurs (has many) PaiementsRG8
    }
}
