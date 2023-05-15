<?php
namespace App\View\Composers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserComposer {
    public function compose($view) {
        $view->with('user', User::find(Auth::id()));
    }
}
