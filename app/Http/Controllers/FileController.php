<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller {
    //
    public function upload(Request $request) {
        logger()->debug($request->file());

        // abort_if(!$request->hasFile('input_file'), 400, __('messages.invalidate'));

        // //
        // $input_file = $request->file('photo');
        // abort_if(!$input_file->isValid(), 400, __('messages.invalidate.upload.dish_menu'));
        $rules = [
            'input_file' => [ 'required', 'file' ]
        ];
        $this->try_validate($request->file(), $rules, []);

        $input_file = $request->file('input_file');
        $path = $input_file->store('upload_files');
        return [ 'path'=>$path ];
    }
}
