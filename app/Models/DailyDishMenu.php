<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyDishMenu extends Model {
    use HasFactory;

    protected $casts = [
        'date' => 'date'
    ];

    public function calendar() {
        return $this->belongsTo(Calendar::class, 'calendar_id');
    }

    public function dish_menus() {
        return $this->belongsToMany(DishMenu::class, 'calendar_id', 'calendar_id')->where('dish_type', $this->dish_type);
    }

    public function scopeDishTypeBy($query, $dish_type) {
        return $query->where('dish_type', $dish_type);
    }

    public function scopeDishTypesBy($query, array $dish_types) {
        return $query->whereIn('dish_type', $dish_types);
    }
}
