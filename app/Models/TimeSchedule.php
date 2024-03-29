<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Flags;
use App\ReserveTypes;

class TimeSchedule extends Model {
    use HasFactory;

    public function reserves() {
        return $this->hasMany('App\Models\Reserve', 'time_schedule_id');
    }

    public function scopeTimePeriodBy($query, $start_time, $end_time) {
        return $query->whereBetween('time_schedules.time', [ $start_time, $end_time ]);
    }

    public function scopeSoccer($query) {
        return $query->where('time_schedules.type', ReserveTypes::VISIT_SOCCER)->where('is_delete', Flags::OFF);
    }

    public function scopeNoSoccer($query) {
        return $query->where('time_schedules.type', ReserveTypes::VISIT_NO_SOCCER)->where('is_delete', Flags::OFF);
    }

    public function scopeLunchbox($query) {
        return $query->where('time_schedules.type', ReserveTypes::LUNCHBOX)->where('is_delete', Flags::OFF);
    }

    public function scopeEnabled($query) {
        return $query->where('time_schedules.is_delete', Flags::OFF);
    }

    public function scopeTimeBy($query, $time) {
        return $query->where('time_schedules.time', '=', $time);
    }
}
