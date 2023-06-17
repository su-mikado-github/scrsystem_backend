<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Mail\PasswordResetMail;

use App\Models\User;

class PasswordResetController extends Controller {
    //
    public function index() {
        return view('pages.login.password_reset.index');
    }

    public function patch(Request $request) {
        $rules = [
            'email' => [ 'required', 'email:rfc' ],
        ];
        $messages = [
        ];
        $this->try_validate($request->all(), $rules, $messages);

        $email = $request->input('email');
        $user = User::enabled()->staffs()->emailBy($email)->first();
        if (!$user) {
            return redirect()->action([ self::class, 'index' ])
                ->with('error', __('messages.invalidate.email'))
            ;
        }

        return $this->trans(function() use($request, $user) {
            $admin_password = Str::random(12);

            $user->admin_password = Hash::make($admin_password);
            $user->reset_token = Str::random(40);
            $this->save($user, $user);

            $mail = new PasswordResetMail($user);
            Mail::to($user)->send($mail);

            return view('pages.login.password_reset.post');
        });
    }
}
