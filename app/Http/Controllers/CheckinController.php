<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;

use App\ReserveTypes;

use App\Models\Reserve;

class CheckinController extends Controller {
    //
    public function index(Request $request, $date=null) {
        $user = $this->user();

        $today = (isset($date) ? Carbon::parse($date) : today());
        $reserve = $user->reserves()->enabled()->dateBy($today)->unCanceled()->first();

        return view('pages.checkin.index')
            ->with('reserve', $reserve)
        ;
    }

    public function post(Request $request, $reserve_id) {
        $user = $this->user();

        $reserve = Reserve::enabled()->find($reserve_id);
        abort_if(!$reserve, 404, __('messages.not_found.reserve'));

        if ($reserve->user_id != $user->id) {
            return redirect()->action([ self::class, 'index' ], [ 'date'=>$reserve->date->format('Y-m-d') ])
                ->with('error', __('messages.error.illegal_user'));
        }

        return $this->trans(function() use($request, $user, $reserve) {
            $reserve->checkin_dt = now();
            $this->save($reserve, $user);

            $reserve = $reserve->fresh();

            //　LINE通知
            $view = ($reserve->type == ReserveTypes::LUNCHBOX ? 'templates.line.lunchbox_checkin' : 'templates.line.visit_checkin');
            $message = view($view)->with('user', $user)->with('reserve', $reserve)->render();
            if (!$this->line_api()->push_messages($user->line_user->line_owner_id, [ $message ])) {
                DB::rollBack();
                return redirect()->action([ self::class, 'index' ], [ 'date'=>$date ])
                    ->with('error', __('messages.error.push_messages'));
            }

            return view('pages.checkin.post')
                ->with('reserve', $reserve)
            ;
        });
    }
}
