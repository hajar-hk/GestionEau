<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    use HasFactory;

    /**
     * Get the client that owns the invoice.
     */


    protected $fillable = [
        'numero_facture',
        'client_id',
        'montants',
        'date_emission',
        'date_echeance',
        'statut',
        'semestre',
    ];
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class); // yaani : Une Facture appartient à (belongs to) un Client.
    }
}
