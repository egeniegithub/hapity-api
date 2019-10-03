<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class PrivacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('privacy_policy');
    }

}
