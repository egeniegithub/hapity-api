<?php

namespace App\Http\Controllers;

use App\User;
use App\Broadcast;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        // $data = Broadcast::find(1);
        // echo "<pre>";
        // print_r($data);
        // exit;
        return view('home');
    }
}
