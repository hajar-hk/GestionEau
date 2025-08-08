<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    
    protected $fillable = [
        'code_client',
        'nom_client',
        'prenom_client',
        'telephone',
        'secteur',
        'email',
        'statut',
    ];
}
