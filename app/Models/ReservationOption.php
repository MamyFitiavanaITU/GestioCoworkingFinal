<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationOption extends Model
{
    
    protected $table = 'reservation_option'; 
    public $timestamps = false;
    protected $fillable = ['reservation_id', 'option_id'];
}
