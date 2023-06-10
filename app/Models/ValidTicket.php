<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\BuyTicket;
use App\Models\UseTicket;

class ValidTicket extends Model {
    use HasFactory;

    protected $casts = [
        'valid_ticket_count' => 'integer'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buy_ticket() {
        return $this->belongsTo(BuyTicket::class, 'buy_ticket_id');
    }

    public function use_tickets() {
        return $this->belongsToMany(UseTicket::class, 'buy_ticket_id', 'buy_ticket_id');
    }

    public function scopeValidateBy($query) {
        return $query->where('valid_ticket_count', '>', 0);
    }
}
