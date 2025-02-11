<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'ref',
        'idEspaceTravail',
        'idClient',
        'dateReservation',
        'heureDebut',
        'duree',
        'statut',
        'idClientReserve'
    ];

    public $timestamps = true;

    public function client()
    {
        return $this->belongsTo(Client::class, 'idClient');
    }


    public function espaceTravail()
    {
        return $this->belongsTo(EspaceTravail::class, 'idEspaceTravail');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'idReservation'); 
    }
    public function options()
    {
        return $this->belongsToMany(Option::class, 'reservation_option');
    }
    public static function getReservationsByDate($date)
    {
        return self::whereDate('dateReservation', $date)->get();
    }

}
