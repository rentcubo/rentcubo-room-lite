<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function user_chat() {

    	return view('sample.user_chat')->with('user_id', 7)->with('provider_id', 1)->with('request_id', 1);
    }

    public function provider_chat() {

    	return view('sample.provider_chat')->with('user_id', 7)->with('provider_id', 1)->with('request_id', 1);
    }
}
