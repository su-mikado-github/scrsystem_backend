<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model {
    use HasFactory;

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function time_schedule() {
        return $this->belongsTo('App\Models\TimeSchedule', 'time_schedule_id');
    }
}
