<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Flags;

use App\Models\User;
use App\Models\Ticket;
use App\Models\UseTicket;
use App\Models\ValidTicket;

class BuyTicket extends Model {
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function use_tickets() {
        return $this->hasMany(UseTicket::class, 'buy_ticket_id');
    }

    public function valid_tickets() {
        return $this->hasMany(ValidTicket::class, 'buy_ticket_id');
    }

    public function scopeEnabled($query) {
        return $query->where('is_delete', Flags::OFF);
    }
}
