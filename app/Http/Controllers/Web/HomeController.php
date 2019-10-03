<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        $broadcast = Broadcast::orderBy('id', 'DESC')->get()->toArray();
        return view('index')->with('broadcast', $broadcast);
    }

}
