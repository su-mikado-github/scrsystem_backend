<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Flags;

class BuyTicket extends Model {
    use HasFactory;

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function ticket() {
        return $this->belongsTo('App\Models\Ticket', 'ticket_id');
    }

    public function scopeEnabled($query) {
        return $query->where('is_delete', Flags::OFF);
    }
}
