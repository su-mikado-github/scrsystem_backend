<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

/**
 * 管理ツール： 管理者（スタッフ）一覧
 */
class StaffsController extends Controller {
    //
    public function index(Request $request) {
        //
        $staffs = User::enabled()->staffs()->orderBy('id')->get();
        return view('pages.admin.staffs.index')
            ->with('staffs', $staffs)
        ;
    }
}
