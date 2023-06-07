<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Flags;
use App\Weekdays;

use App\Models\DishMenu;
use App\Models\DailyDishMenu;
use App\Models\MonthCalendar;
use App\Models\Reserve;

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
        'date' => 'date'
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

    public function scopeYearMonthBy($query, $year, $month) {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeDateBy($query, $date) {
        return $query->where('date', $date);
    }

    public function scopePeriodBy($query, $start_date, $end_date) {
        return $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);
    }
}
