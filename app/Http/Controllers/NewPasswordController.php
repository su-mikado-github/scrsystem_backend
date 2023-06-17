<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class NewPasswordController extends Controller {
    //
    public function index(Request $request, $reset_token) {
        $staff = User::enabled()->staffs()->resetTokenBy($reset_token)->first();
        abort_if(!$staff, 404, __('messages.not_found.staff'));

        //
        return view('pages.new_password.index')
            ->with('staff', $staff)
            ->with('reset_token', $reset_token)
        ;
    }

    public function patch(Request $request, $user_id, $reset_token) {
        $staff = User::enabled()->staffs()->resetTokenBy($reset_token)->find($user_id);
        if (!$staff) {
            return redirect()->action([ self::class, 'index' ], compact('reset_token'))
                ->with('error', __('messages.not_found.staff'))
            ;
        }

        $rules = [
            'password' => [ 'required', 'min:8', 'max:32' ],
            'password_confirm' => [ 'required', 'same:password' ],
        ];
        $messages = [
            'password.min' => 'パスワードは8桁以上にしてください。',
            'password.max' => 'パスワードは32桁以内にしてください。',
            'password_confirm.same' => '「パスワード」と一致しません。',
        ];
        $this->try_validate($request->all(), $rules, $messages);

        return $this->trans(function() use($request, $staff) {
            $password = $request->input('password');

            $staff->admin_password = Hash::make($password);
            $this->save($staff, $staff);

            return view('pages.new_password.patch');
        });
    }
}
