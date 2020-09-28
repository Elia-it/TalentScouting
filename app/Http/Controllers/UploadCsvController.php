<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploadCsvController extends Controller
{

    public function store(Request $request)
    {
        //
        $validator = Validator::make( $request->all(), [
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);

        if ($validator->fails()){
            return response()->json( $validator->errors(), 400);
        }

        if($request->hasFile('file')) {

            $path_file = $request->file('file')->store('public');
            return response()->json(['file' => $path_file]);
        }

        return response()->json(['message' => 'Invalid file']);

    }

}
