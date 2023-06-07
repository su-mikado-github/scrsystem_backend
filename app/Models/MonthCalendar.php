<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Calendar;

class MonthCalendar extends Model
{
    use HasFactory;

    protected $appends = [
    ];

    protected $casts = [
        'date' => 'date',
        'last_date' => 'date',
        'previous_date' => 'date',
        'next_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function calendars() {
        return $this->hasMany(Calendar::class, 'year', 'year')->where('month', $this->month);
    }

    public function scopeDateBy($query, $date) {
        return $query->where('date', '<=', $date)->where('last_date', '>=', $date);
    }

    public function scopeYearMonthBy($query, $year, $month) {
        return $query->where('year', $year)->where('month', $month);
    }

    public function contains($date) {
        if ($date instanceof Calendar) {
            return ($this->date <= $date->date && $this->last_date >= $date->date);
        }
        else if ($date instanceof Carbon) {
            return ($this->date <= $date && $this->last_date >= $date);
        }
        else if (is_string($date)) {
            $carbon = Carbon::parse($date);
            return ($this->date <= $carbon && $this->last_date >= $carbon);
        }
        else {
            return false;
        }
    }

    public function isOver($date) {
        if ($date instanceof Calendar) {
            return ($this->last_date < $date->date->getTimestamp());
        }
        else if ($date instanceof Carbon) {
            return ($this->last_date < $date);
        }
        else if (is_string($date)) {
            return ($this->last_date < Carbon::parse($date));
        }
        else {
            return false;
        }
    }

    public function isUnder($date) {
        if ($date instanceof Calendar) {
            return ($this->date > $date->date);
        }
        else if ($date instanceof Carbon) {
            return ($this->date > $date);
        }
        else if (is_string($date)) {
            return ($this->date > Carbon::parse($date));
        }
        else {
            return false;
        }
    }

    public function getPeriodAttribute() {
        return CarbonPeriod::create($this->start_date, $this->end_date);
    }
}
