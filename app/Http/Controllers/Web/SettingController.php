<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class SettingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function settings()
	{
        $userinfo = User::with('profile')->where('id', Auth::id())->first()->toArray();
    	return view('setting',compact('userinfo'));     
	}
}
