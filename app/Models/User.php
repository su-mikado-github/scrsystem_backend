<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Flags;
use App\SortTypes;

use App\Models\LineUser;
use App\Models\Affiliation;
use App\Models\SchoolYear;
use App\Models\AffiliationDetail;
use App\Models\BuyTicket;
use App\Models\Reserve;
use App\Models\UseTicket;
use App\Models\ValidTicket;

class User extends Authenticatable {
    use HasFactory;

    protected $casts = [
        'birthday' => 'date'
    ];

    protected $appends = [
        'last_ticket_count'
    ];

    public function line_user() {
        return $this->belongsTo(LineUser::class, 'line_user_id');
    }

    public function affiliation() {
        return $this->belongsTo(Affiliation::class, 'affiliation_id');
    }

    public function school_year() {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function affiliation_detail() {
        return $this->belongsTo(AffiliationDetail::class, 'affiliation_detail_id');
    }

    public function buy_tickets() {
        return $this->hasMany(BuyTicket::class, 'user_id');
    }

    public function use_tickets() {
        return $this->hasMany(UseTicket::class, 'user_id');
    }

    public function valid_tickets() {
        return $this->hasMany(ValidTicket::class, 'user_id');
    }

    public function reserves() {
        return $this->hasMany(Reserve::class, 'user_id');
    }

    public function scopeSettings($query) {
        return $query->where('is_initial_setting', Flags::ON);
    }

    public function scopeEnabled($query) {
        return $query->where('is_delete', Flags::OFF);
    }

    public function scopeFullNameOrder($query, $sort_type_id) {
        $sort_type = SortTypes::of($sort_type_id, SortTypes::ASC());
        return $query->orderBy('users.last_name_kana', $sort_type->sql_order_by)->orderBy('users.first_name_kana', $sort_type->sql_order_by);
    }

    public function scopeAffiliationOrder($query, $sort_type_id) {
        $sort_type = SortTypes::of($sort_type_id, SortTypes::ASC());
        return $query
            ->leftJoin('affiliations', 'affiliations.id', '=', 'users.affiliation_id')
            ->orderBy('affiliations.display_order', $sort_type->sql_order_by);
    }

    public function scopeAffiliationDetailOrder($query, $sort_type_id) {
        $sort_type = SortTypes::of($sort_type_id, SortTypes::ASC());
        return $query
            ->leftJoin('affiliation_details', 'affiliation_details.id', '=', 'users.affiliation_detail_id')
            ->orderBy('affiliation_details.display_order', $sort_type->sql_order_by);
    }

    public function scopeSchoolYearOrder($query, $sort_type_id) {
        $sort_type = SortTypes::of($sort_type_id, SortTypes::ASC());
        return $query
            ->leftJoin('school_years', 'school_years.id', '=', 'users.school_year_id')
            ->orderBy('school_years.display_order', $sort_type->sql_order_by);
    }

    public function getLastTicketCountAttribute() {
        $valid_ticket = $this->valid_tickets()->where('valid_ticket_count', '>', 0)
            ->selectRaw('user_id, SUM(valid_ticket_count) as valid_ticket_count')
            ->groupBy('user_id')
            ->first();
        return (op($valid_ticket)->valid_ticket_count ?? 0);
    }

    public function getAgeAttribute() {
        return op($this->birthday)->age;
    }
}
