<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Flags;

use App\Models\User;
use App\Models\Calendar;
use App\Models\TimeSchedule;
use App\Models\EmptyState;

class Reserve extends Model {
    use HasFactory;

    protected $casts = [
        'date' => 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function calendar() {
        return $this->belongsTo(Calendar::class, 'date', 'date');
    }

    public function time_schedule() {
        return $this->belongsTo(TimeSchedule::class, 'time_schedule_id');
    }

    public function scopeDateTimeBy($query, $date, $schedule_time_id) {
        return $query->where('date', $date)->where('schedule_time_id', $schedule_time_id);
    }

    public function scopeCalendarScheduleTimeBy($query, Calendar $calendar, ScheduleTime $schedule_time) {
        return $query->where('date', $calendar->date)->where('schedule_time_id', $schedule_time->id);
    }

    public function scopeEnabled($query) {
        return $query->where('is_delete', Flags::OFF);
    }

    public function rebuild_empty_states() {
        return EmptyState::rebuild($this->date, $this->time, $this->end_time);
    }
}
