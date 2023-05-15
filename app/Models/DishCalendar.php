<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishCalendar extends Model {
    use HasFactory;

    public function calendar() {
        return $this->belongsTo('App\Models\Calendar', 'calendar_id');
    }

    public function dish() {
        return $this->belongsTo('App\Models\Dish', 'dish_id');
    }
}
