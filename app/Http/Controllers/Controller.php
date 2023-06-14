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

use App\Flags;

use App\LineApi;

use App\Models\User;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $line_api = null;

    public function __construct(LineApi $line_api) {
        $this->line_api = $line_api;
    }

    protected function line_api() {
        return $this->line_api;
    }

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

    protected function remove(Model $model) {
        if (isset($model)) {
            $model->is_delete = Flags::ON;
            $model->save();
        }
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

    protected function time_to_mins($time, $default=null) {
        if (isset($time) && preg_match('/^[0-9]{2}[:][0-9]{2}[:][0-9]{2}$/i', $time)) {
            list($h, $m, $s) = explode(":", $time);
            if (0 <= $h && 0 <= $m && $m < 60 && 0 <= $s && $s < 60) {
                return ($h * 60 + $m);
            }
        }
        return $default;
    }

    protected function mins_to_time($mins, $default=null) {
        if (isset($mins) && is_integer($mins) && 0 <= $mins) {
            $h = intval(floor($mins / 60));
            $m = ($mins % 60);
            return sprintf('%02d:%02d:00', $h, $m);
        }
        return $default;
    }
}
