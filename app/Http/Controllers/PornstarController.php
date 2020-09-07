<?php

namespace App\Http\Controllers;

use App\Pornstars;
use Illuminate\Http\Request;

class PornstarController extends Controller
{
    //

    public function getPornstars(){
        $all_pornstars = Pornstars::all();

        return view('pornstars', compact('all_pornstars'));
    }
}
