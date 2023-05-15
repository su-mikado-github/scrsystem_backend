<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Flags;

class UseTicket extends Model {
    use HasFactory;

    public function reserve() {
        return $this->belongsTo('App\Models\Reserve', 'reserve_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function buy_ticket() {
        return $this->belongsTo('App\Models\BuyTicket', 'buy_ticket_id');
    }

    public function scopeEnabled($query) {
        return $query->where('is_delete', Flags::OFF);
    }
}
