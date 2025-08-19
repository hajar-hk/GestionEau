<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory;

    protected $table = 'declarations';

    protected $fillable = [
        'numero_declaration',
        'periode',
        'montant_global',
        'date_declaration',
        'statut',
    ];

    // Une déclaration a plusieurs bordereaux
    public function bordereaux()
    {
        return $this->hasMany(BordereauRg12::class);
    }
}
