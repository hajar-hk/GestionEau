<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{


    use HasFactory;

    protected $fillable = [
        'code_client',
        'nom_client',
        'prenom_client',
        'telephone',
        'secteur',
        'email',
        'statut',
    ];
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class); // "Un Client a plusieurs (has many) Factures.
    }
}
