<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
}
