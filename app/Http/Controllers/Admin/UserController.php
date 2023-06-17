<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller {
    //
    public function index(Request $request, $user_id) {
        $target_user = User::with([ 'affiliation', 'affiliation_detail', 'school_year', 'reserves'=>function($query) { $query->orderBy('date'); }, 'reserves.calendar', 'buy_tickets' ])->enabled()->find($user_id);
        if (empty($target_user)) {
            return redirect()->route('admin.users')
                ->with('error', __('messages.nor_found.user'))
            ;
        }

        return view('pages.admin.user.index')
            ->with('target_user', $target_user)
        ;
    }

    public function delete(Request $request, $user_id) {
        $user = User::enabled()->find($user_id);
        if (!$user) {
            return redirect()->route('admin.users')
                ->with('error', __('messages.nor_found.user'))
            ;
        }

        return $this->trans(function() use($request, $user) {
            $this->remove($user, $user);

            return redirect()->route('admin.users')
                ->with('success', __('messages.success.user.delete'))
            ;
        });
    }
}
