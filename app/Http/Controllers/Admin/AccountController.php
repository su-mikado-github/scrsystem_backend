<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Mail\PasswordChangeMail;

class AccountController extends Controller {
    //
    public function index(Request $request) {
        return view('pages.admin.account.index');
    }

    public function put(Request $request) {
        $user = $this->user();

        $rules = [
            'last_name' => [ 'required', 'max:256' ],
            'first_name' => [ 'required', 'max:256' ],
            'email' => [ 'required', 'email:rfc' ],
            'password' => [ 'nullable', 'min:8', 'max:32' ],
            'password_confirm' => [ Rule::requiredIf(!empty($request->input('password'))), 'nullable', 'same:password' ],
        ];
        $messages = [
            'last_name.max' => '姓は256桁位以内にしてください。',
            'first_name.max' => '名は256桁位以内にしてください。',
            'last_name.max' => '',
            'password.min' => 'パスワードは8桁以上にしてください。',
            'password.max' => 'パスワードは32桁以内にしてください。',
            'password_confirm.required_if' => 'パスワードを入力している場合は、パスワード（確認用）も入力してください。',
            'password_confirm.same' => 'パスワードと一致していません。',
        ];
        $this->try_validate($request->all(), $rules, $messages);

        return $this->trans(function() use($request, $user) {
            $user->last_name = $request->input('last_name');
            $user->first_name = $request->input('first_name');
            $user->email = $request->input('email');

            $password = $request->input('password');
            if (!empty($password)) {
                $user->admin_password = Hash::make($password);
            }
            $this->save($user, $user);

            if (!empty($password)) {
                $mail = new PasswordChangeMail($user);
                Mail::to($user)->send($mail);
            }

            return redirect()->action([ self::class, 'index' ])
                ->with('success', __('messages.success.account.put'))
            ;
        });
    }
}
