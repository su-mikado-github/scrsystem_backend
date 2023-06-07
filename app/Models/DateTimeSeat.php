<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Seat;
use App\Models\Calendar;
use App\Models\TimeSchedule;
use App\Models\Time;

class DateTimeSeat extends Model {
    use HasFactory;

    public function seat() {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function calendar() {
        return $this->belongsTo(Calendar::class, 'calendar_id');
    }

    public function time() {
        return $this->belongsTo(Time::class, 'time_id');
    }

    public function scopeCalendarBy($query, Calendar $calendar) {
        return $query->where('calendar_id', $calendar->id);
    }

    public function scopeDateBy($query, $date) {
        return $query->whereDate('date', $date);
    }

    public function scopeYearMonthBy($query, $year, $month) {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopePeriodBy($query, $from_date, $to_date) {
        return $query->whereDate('date', '>=', $from_date)->whereDate('date', '<=', $to_date);
    }

    public function scopeTimeRangeBy($query, $start_time, $end_time) {
        return $query->where('time', '>=', $start_time)->where('time', '<=', $end_time);
    }

    public function scopeTimeBy($query, $time) {
        if ($time instanceof Time) {
            return $query->where('time', $time->time);
        }
        else {
            return $query->where('time', $time);
        }
    }
}
