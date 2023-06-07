<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use App\Flags;

use App\Models\User;

class LoginController extends Controller {
    //
    public function index(Request $request) {
        return view('pages.login.index');
    }

    public function post(Request $request) {
        $rules = [
            'email' => [ 'required', 'email:rfc' ],
            'password' => [ 'required' ]
        ];
        $this->try_validate($request->input(), $rules, null, function($validator) use($request) {
            if ($request->filled([ 'email', 'password' ])) {
                $user = User::where('is_delete', Flags::OFF)->where('is_admin', Flags::ON)->where('email', $request->input('email'))->first();
                if (empty($user) || Hash::check($user->admin_password, $request->input('password'))) {
                    $validator->errors()->add('unauthorized', __('validation.unauthorized'));
                }
            }
        });

        return $this->trans(function() use($request) {
            $user = User::where('is_delete', Flags::OFF)->where('is_admin', Flags::ON)->where('email', $request->input('email'))->first();
            $user->last_login_dt = now();
            $this->save($user, $user);

            Auth::login($user);

            return redirect()->route('admin');
        });
    }

    public function logout() {
        Auth::logout();

        return redirect()->route('login');
    }
}
