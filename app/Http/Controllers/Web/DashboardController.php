<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class DashboardController extends Controller
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
        $broadcasts = Broadcast::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('home', compact('userdata', 'broadcasts'));
    }

}
