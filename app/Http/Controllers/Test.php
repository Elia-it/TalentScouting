<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\simple_html_dom;
use Sunra\PhpSimple\HtmlDomParser;
use App\AmateurModel;

class Test extends Controller
{
    //
    public function models(){

        $models = AmateurModel::all();

        return view('test', compact('models'));
    }
}
