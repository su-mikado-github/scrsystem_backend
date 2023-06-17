<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Flags;

use App\Models\User;
use App\Models\Ticket;
use App\Models\UseTicket;
use App\Models\ValidTicket;

class BuyTicket extends Model {
    use HasFactory;

    protected $casts = [
        'buy_dt' => 'datetime',
    ];

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

    public function getValidTicketCountAttribute() {
        $use_ticket_count = UseTicket::enabled()->where('user_id', $this->user_id)->where('use_dt', '<=', $this->buy_dt)->count();
        $buy_ticket_count = BuyTicket::enabled()->where('user_id', $this->user_id)->where('buy_dt', '<=', $this->buy_dt)->sum('ticket_count');
        return (($buy_ticket_count ?? 0) - $use_ticket_count);
    }
}
