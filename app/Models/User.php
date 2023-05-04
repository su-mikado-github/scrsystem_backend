<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use HasFactory;

    public function user() {
        return $this->belongsTo('App\LineUser', 'line_user_id');
    }
}
