<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\DishTypes;

class DishMenu extends Model {
    use HasFactory;

    public function calendar() {
        return $this->belongsTo('App\Models\Calendar', 'calendar_id');
    }

    public function scopeDishTypeBy($query, $dish_type) {
        return $query->where('dish_type', ($dish_type instanceof DishTypes ? $dish_type->id : $dish_type));
    }

    public function scopeDishTypesBy($query, array $dish_types) {
        return $query->whereIn('dish_type', $dish_types);
    }
}
