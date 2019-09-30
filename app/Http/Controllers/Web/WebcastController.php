<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class WebcastController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function start_web_cast(){
        $data = User::with('profile')->where('id', Auth::id())->first()->toArray();
    	return view('webcast',compact('data'));
    }

    public function create_content(){
         $data = User::with('profile')->where('id', Auth::id())->first()->toArray();
    	return view('create-content',compact('data'));
    }
}
