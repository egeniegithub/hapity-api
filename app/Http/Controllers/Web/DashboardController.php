<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

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
        $plugin_ids = '';
        $userdata = User::with(['profile', 'plugins'])->where('id', Auth::id())->first()->toArray();
        $broadcasts = Broadcast::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        

        return view('home', compact('userdata', 'broadcasts', 'plugin_ids'));
    }

}
