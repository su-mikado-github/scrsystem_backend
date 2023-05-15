<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Flags;

class User extends Authenticatable {
    use HasFactory;

    protected $appends = [
        'last_ticket_count'
    ];

    public function line_user() {
        return $this->belongsTo('App\Models\LineUser', 'line_user_id');
    }

    public function affiliation() {
        return $this->belongsTo('App\Models\Affiliation', 'affiliation_id');
    }

    public function school_year() {
        return $this->belongsTo('App\Models\SchoolYear', 'school_year_id');
    }

    public function affiliation_detail() {
        return $this->belongsTo('App\Models\AffiliationDetail', 'affiliation_detail_id');
    }

    public function buy_tickets() {
        return $this->hasMany('App\Models\BuyTicket', 'user_id');
    }

    public function use_tickets() {
        return $this->hasMany('App\Models\UseTicket', 'user_id');
    }

    public function scopeEnabled($query) {
        return $query->where('is_delete', Flags::OFF);
    }

    public function getLastTicketCountAttribute() {
        $buy_ticket_count = $this->buy_tickets()->enabled()->sum('ticket_count');
        $use_ticket_count = $this->use_tickets()->enabled()->count();
        return $buy_ticket_count - $use_ticket_count;
    }
}
