<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
// use App\Libraries\Wowza_lib;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        // $wowza = new Wowza_lib();
        // $wowza->get_server_stats();
        
        $broadcast = Broadcast::orderBy('id', 'DESC')->get()->toArray();
        return view('index')->with('broadcast', $broadcast);
    }

}
