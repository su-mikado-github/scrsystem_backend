<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Flags;
use App\ReserveTypes;
use App\Weekdays;

use App\Models\DishMenu;
use App\Models\DailyDishMenu;
use App\Models\MonthCalendar;
use App\Models\Reserve;
use App\Models\EmptyState;

class Calendar extends Model {
    use HasFactory;

    public static function find_last_sunday($today=null) {
        return Calendar::whereDate('date', '<=', $today ?? today())
            ->where('weekday', '=', Weekdays::SUNDAY)
            ->orderBy('date', 'desc')
            ->first();
        ;
    }

    public static function findNextSunday($today=null) {
        return Calendar::whereDate('date', '>', $today ?? today())
            ->where('weekday', '=', Weekdays::SUNDAY)
            ->orderBy('date')
            ->first();
        ;
    }

    public function scopeRange($query, $start_date, $end_date) {
        return $query->whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
        ;
    }

    protected $casts = [
        'date' => 'date',
        'previous_date' => 'date',
        'next_date' => 'date',
    ];

    public function dish_menus() {
        return $this->hasMany(DishMenu::class);
    }

    public function daily_dish_menus() {
        return $this->hasMany(DailyDishMenu::class);
    }

    public function month_calendar() {
        return $this->hasOne(MonthCalendar::class, 'year', 'year')->where('month', $this->month);
    }

    public function reserves() {
        return $this->hasMany(Reserve::class, 'date', 'date')->where('is_delete', Flags::OFF);
    }

    public function getDiningHallReserveSummaryAttribute() {
        return $this->reserves()
            ->where('is_delete', Flags::OFF)
            ->whereIn('type', [ ReserveTypes::VISIT_SOCCER, ReserveTypes::VISIT_NO_SOCCER ])
            ->whereExists(function($sub_query) {
                $sub_query
                    ->selectRaw(1)
                    ->from('users')
                    ->where('users.is_delete', Flags::OFF)
                    ->whereRaw('users.id = reserves.user_id')
                ;
            })
            ->selectRaw('user_id,date,SUM(reserve_count) as reserve_count,SUM(IF(checkin_dt IS NOT NULL, reserve_count, 0)) as checkin_reserve_count, SUM(IF(cancel_dt IS NOT NULL, reserve_count, 0)) as cancel_reserve_count')
            ->groupByRaw('user_id,date')
            ->first()
        ;
    }

    public function getLunchboxReserveSummaryAttribute() {
        return $this->reserves()
            ->where('is_delete', Flags::OFF)
            ->where('type', ReserveTypes::LUNCHBOX)
            ->whereExists(function($sub_query) {
                $sub_query
                    ->selectRaw(1)
                    ->from('users')
                    ->where('users.is_delete', Flags::OFF)
                    ->whereRaw('users.id = reserves.user_id')
                ;
            })
            ->selectRaw('user_id,date,SUM(reserve_count) as reserve_count,SUM(IF(checkin_dt IS NOT NULL, reserve_count, 0)) as checkin_reserve_count, SUM(IF(cancel_dt IS NOT NULL, reserve_count, 0)) as cancel_reserve_count')
            ->groupByRaw('user_id,date')
            ->first()
        ;
    }

    public function empty_states() {
        return $this->hasMany(EmptyState::class, 'calendar_id')->where('is_delete', Flags::OFF);
    }

    public function empty_state_summary() {
        return $this->hasOne(EmptyState::class, 'calendar_id')
            ->where('is_delete', Flags::OFF)
            ->selectRaw('date,year,month,day,weekday,SUM(seat_count) as seat_count,SUM(empty_seat_count) as empty_seat_count')
            ->groupByRaw('date,year,month,day,weekday')
        ;
    }

    public function scopeYearMonthBy($query, $year, $month) {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeDateBy($query, $date) {
        return $query->where('date', $date);
    }

    public function scopePeriodBy($query, $start_date, $end_date) {
        return $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);
    }

    public function scopeEnabled($query) {
        return $query->where('is_delete', Flags::OFF);
    }

    public function getPreviousDateAttribute() {
        return $this->date->copy()->subDays();
    }

    public function getNextDateAttribute() {
        return $this->date->copy()->addDays();
    }
}
