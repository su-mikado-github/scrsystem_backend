<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function ok(array $result, $message=null) {
        return response($message, 200)->json([
            'result' => $result
        ]);
    }

    protected function ok_message($message, array $result) {
        return response($message, 200)->json([
            'result' => $result
        ]);
    }

    protected function redirect($url) {
        return redirect($url);
    }

    protected function user() {
        return User::find(Auth::id());
    }

    protected function save(Model $model, User $user=null) {
        if (isset($model->id)) {
            $model->data_version ++;
        }
        else {
            $model->created_id = optional($user)->id ?? 0;
        }
        $model->updated_id = optional($user)->id ?? 0;
        $model->save();
    }

    protected function validate(array $input, array $rules, array $messages=null, callable $after=null) {
        $validator = Validator::make($input, $rules, $messages ?? []);
        if (isset($after)) {
            $validator->after($after);
        }
        return $validator;
    }

    protected function try_validate(array $input, array $rules, array $messages=null, callable $after=null) {
        $validator = Validator::make($input, $rules, $messages ?? []);
        if (isset($after)) {
            $validator->after($after);
        }
        $validator->validate();
        return $validator;
    }

    protected function trans(callable $scope) {
        return DB::transaction($scope);
    }
}
