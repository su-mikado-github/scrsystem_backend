<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Flags;
use App\SortTypes;

use App\Models\User;
use App\Models\Ticket;
use App\Models\UseTicket;
use App\Models\ValidTicket;

class BuyTicket extends Model {
    use HasFactory;

    protected $casts = [
        'buy_dt' => 'datetime',
        'payment_dt' => 'datetime',
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
        return $query->where('buy_tickets.is_delete', Flags::OFF);
    }

    public function scopeYearMonthBy($query, $year, $month) {
        return $query->whereYear('buy_tickets.buy_dt', $year)->whereMonth('buy_tickets.buy_dt', $month);
    }

    public function scopeUnpaid($query) {
        return $query->whereNull('buy_tickets.payment_dt');
    }

    public function scopeJoinOn($query) {
        return $query
            ->join('users', 'users.id', '=', 'buy_tickets.user_id')
            ->join('affiliations', 'affiliations.id', '=', 'users.affiliation_id')
            ->join('affiliation_details', 'affiliation_details.id', '=', 'users.affiliation_detail_id')
            ->leftJoin('school_years', 'school_years.id', '=', 'users.school_year_id')
        ;
    }

    public function scopeBuyDtOrder($query, $sort_type_id) {
        $sort_type = SortTypes::of($sort_type_id, SortTypes::ASC());
        return $query->orderBy('buy_tickets.buy_dt', $sort_type->sql_order_by);
    }

    public function scopeFullNameOrder($query, $sort_type_id) {
        $sort_type = SortTypes::of($sort_type_id, SortTypes::ASC());
        return $query->orderBy('users.last_name_kana', $sort_type->sql_order_by)->orderBy('users.first_name_kana', $sort_type->sql_order_by);
    }

    public function scopeAffiliationOrder($query, $sort_type_id) {
        $sort_type = SortTypes::of($sort_type_id, SortTypes::ASC());
        return $query->orderBy('affiliations.display_order', $sort_type->sql_order_by);
    }

    public function scopeAffiliationDetailOrder($query, $sort_type_id) {
        $sort_type = SortTypes::of($sort_type_id, SortTypes::ASC());
        return $query->orderBy('affiliation_details.display_order', $sort_type->sql_order_by);
    }

    public function scopeSchoolYearOrder($query, $sort_type_id) {
        $sort_type = SortTypes::of($sort_type_id, SortTypes::ASC());
        return $query->orderBy('school_years.display_order', $sort_type->sql_order_by);
    }

    public function getValidTicketCountAttribute() {
        $use_ticket_count = UseTicket::enabled()->where('user_id', $this->user_id)->where('use_dt', '<=', $this->buy_dt)->count();
        $buy_ticket_count = BuyTicket::enabled()->where('user_id', $this->user_id)->where('buy_dt', '<=', $this->buy_dt)->sum('ticket_count');
        return (($buy_ticket_count ?? 0) - $use_ticket_count);
    }
}
