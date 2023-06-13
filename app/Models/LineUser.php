<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Flags;

class LineUser extends Model {
    use HasFactory;

    public function user() {
        return $this->hasOne('App\Models\User', 'line_user_id');
    }

    public function scopeLineOwnerIdBy($query, $line_owner_id) {
        return $query->where('line_owner_id', $line_owner_id);
    }

    public function scopeTokenBy($query, $token) {
        return $query->where('token', $token);
    }

    public function scopeEnabled($query) {
        return $query->where('is_delete', Flags::OFF);
    }


}
