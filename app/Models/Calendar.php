<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Weekdays;

class Calendar extends Model {
    use HasFactory;

    protected $casts = [
        'date' => 'date'
    ];

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
}
