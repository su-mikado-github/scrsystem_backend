<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Flags;
use App\ReserveTypes;

use App\Models\User;
use App\Models\Calendar;
use App\Models\TimeSchedule;
use App\Models\UseTicket;
use App\Models\EmptyState;
use App\Models\ReserveSeat;
use App\Models\Seat;

class Reserve extends Model {
    use HasFactory;

    protected $casts = [
        'date' => 'date',
        'remind_dt' => 'datetime',
        'checkin_dt' => 'datetime',
        'cancel_dt' => 'datetime'
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

    public function use_tickets() {
        return $this->hasMany(UseTicket::class, 'reserve_id');
    }

    public function reserve_seats() {
        return $this->hasMany(ReserveSeat::class, 'reserve_id');
    }

    public function scopeUserBy($query, User $user) {
        return $query->where('user_id', $user->id);
    }

    public function scopeDateBy($query, $date) {
        return $query->where('date', $date);
    }

    public function scopeDateTimeBy($query, $date, $time) {
        return $query->where('reserves.date', $date)->where('reserves.time', $time);
    }

    public function scopeTypesBy($query, array $types) {
        return $query->whereIn('reserves.type', $types);
    }

    public function scopeLunchboxBy($query) {
        return $query->whereIn('reserves.type', [ ReserveTypes::LUNCHBOX ]);
    }

    public function scopeDiningHallBy($query) {
        return $query->whereIn('reserves.type', [ ReserveTypes::VISIT_SOCCER, ReserveTypes::VISIT_NO_SOCCER ]);
    }

    public function scopeNoCheckin($query) {
        return $query->whereNull('reserves.checkin_dt');
    }

    public function scopeUnCanceled($query) {
        return $query->whereNull('reserves.cancel_dt');
    }

    public function scopeCalendarScheduleTimeBy($query, Calendar $calendar, ScheduleTime $schedule_time) {
        return $query->where('reserves.date', $calendar->date)->where('reserves.schedule_time_id', $schedule_time->id);
    }

    public function scopeEnabled($query) {
        return $query->where('reserves.is_delete', Flags::OFF);
    }

    public function scopeTypeOrdered($query) {
        return $query->orderBy('reserves.type');
    }

    public function scopeDateOrdered($query) {
        return $query->orderBy('reserves.date');
    }

    public function scopeTimeOrdered($query) {
        return $query->orderBy('reserves.time');
    }

    public function rebuild_empty_states() {
        return EmptyState::rebuild($this->date, $this->time, $this->end_time);
    }

    public function getSeatGroupsAttribute() {
        return Seat::enabled()->whereIn('id', $this->reserve_seats->pluck('seat_id'))
            ->selectRaw('seat_group_no, COUNT(*) as seat_count')
            ->groupBy('seat_group_no')
            ->orderBy('seat_group_no')
            ->get()
        ;
    }
}
