<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveSeat extends Model {
    use HasFactory;

    public function reserve() {
        return $this->belongsTo('App\Models\Reserve', 'reserve_id');
    }

    public function seat() {
        return $this->belongsTo('App\Models\Seat', 'seat_id');
    }
}
