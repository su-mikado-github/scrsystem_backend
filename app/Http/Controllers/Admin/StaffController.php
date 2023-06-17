<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Admin\StaffsController;

use App\Flags;

use App\Mail\CreateStaffMail;
use App\Mail\PasswordResetMail;

use App\Models\User;

/**
 * 管理ツール：管理者（スタッフ）
 */
class StaffController extends Controller {
    //
    public function post(Request $request) {
        $user = $this->user();

        $rules = [
            'last_name' => [ 'required' ],
            'first_name' => [ 'required' ],
            'email' => [ 'required','email:rfc' ],
        ];
        $messages = [
        ];
        $validator = $this->validate($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->action([ StaffsController::class, 'index' ])
                ->withErrors($validator)
                ->withInput()
                ->with('method', 'post')
            ;
        }

        return $this->trans(function() use($request, $user) {
            $admin_password = Str::random(12);

            $staff = new User();
            $staff->last_name = $request->input('last_name');
            $staff->first_name = $request->input('first_name');
            $staff->email = $request->input('email');
            $staff->is_admin = Flags::ON;
            $staff->admin_password = Hash::make($admin_password);
            $staff->is_initial_setting = Flags::ON;
            $this->save($staff, $user);

            //管理者登録のメール通知
            $mail = new CreateStaffMail($staff, $admin_password);
            Mail::to($staff)->send($mail);

            return redirect()->action([ StaffsController::class, 'index' ])
                ->with('success', __('messages.success.staff.post'))
            ;
        });
    }

    public function put(Request $request, $user_id) {
        $user = $this->user();

        $staff = User::enabled()->staffs()->find($user_id);
        if (!$staff) {
            return redirect()
                ->action([ StaffsController::class, 'index' ])
                ->with('error', __('messages.not_found.staff'))
                ->with('method', 'put')
                ->with('user_id', $user_id)
            ;
        }

        $rules = [
            'last_name' => [ 'required' ],
            'first_name' => [ 'required' ],
            'email' => [ 'required','email:rfc' ],
        ];
        $messages = [
        ];
        $validator = $this->validate($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->action([ StaffsController::class, 'index' ])
                ->withErrors($validator)
                ->withInput()
                ->with('method', 'put')
                ->with('user_id', $user_id)
            ;
        }

        return $this->trans(function() use($request, $user, $staff) {
            $is_password_reset = ($request->input('is_password_reset', Flags::OFF) == Flags::ON);

            $admin_password = Str::random(12);

            $staff->last_name = $request->input('last_name');
            $staff->first_name = $request->input('first_name');
            $staff->email = $request->input('email');
            $staff->is_admin = Flags::ON;
            if ($is_password_reset) {
                $staff->admin_password = Hash::make($admin_password);
                $staff->reset_token = Str::random(40);
            }
            $staff->is_initial_setting = Flags::ON;
            $this->save($staff, $user);

            //管理者編集（パスワード・リセット）のメール通知
            if ($is_password_reset) {
                $mail = new PasswordResetMail($staff);
                Mail::to($staff)->send($mail);
            }

            return redirect()->action([ StaffsController::class, 'index' ])
                ->with('success', __('messages.success.staff.put'))
            ;
        });
    }

    public function delete(Request $request, $user_id) {
        $user = $this->user();

        $staff = User::enabled()->find($user_id);
        if (!$staff) {
            return redirect()
                ->action([ StaffsController::class, 'index' ])
                ->with('error', __('messages.not_found.staff'))
            ;
        }

        return $this->trans(function() use($request, $user, $staff) {
            $this->remove($staff, $user);

            return redirect()->action([ StaffsController::class, 'index' ])
                ->with('success', __('messages.success.staff.delete'))
            ;
        });
    }
}
