<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Broadcast;

class MainController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	$userdata = User::with('profile')->where('id', Auth::id())->first()->toArray();
    	$broadcasts = Broadcast::orderBy('id','DESC')->get();
    	return view('home',compact('userdata','broadcasts'));
    }
    
}