<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Flags;

class Ticket extends Model {
    use HasFactory;

    public function scopeEnabled($query) {
        return $query->where('is_delete', Flags::OFF);
    }
}
