<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';

    protected $fillable = [
        'code',
        'nomOption',
        'prix',
    ];
    // public function reservations()
    // {
    //     return $this->belongsToMany(Reservation::class, 'reservation_option', 'option_id', 'reservation_id');
    // }
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_option');
    }


}
