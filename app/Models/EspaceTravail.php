<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspaceTravail extends Model
{
    use HasFactory;

    protected $table = 'espace_travail';

    protected $fillable = [
        'nom',
        'prix_heure'
    ];
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idEspaceTravail');
    }
    // public static function getReservationsByDate($date)
    // {
    //     return self::whereDate('dateReservation', $date)->get();
    // }
}
