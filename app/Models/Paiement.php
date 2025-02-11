<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    const STATUT_EN_ATTENTE = 1;
    const STATUT_VALIDE = 2;

    protected $table = 'paiements';
    protected $fillable = [
        'idReservation',
        'referencesPaiements',
        'datePaiement',
        'statutValidation',
        'montant'
    ];

    public $timestamps = true;  

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idReservation');
    }
}
