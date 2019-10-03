<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReportBroadcast;
use App\ReportUser;
use App\Broadcast;

class AdminController extends Controller
{
    //
    public function index(){

        $reported_broadcast_count   = ReportBroadcast::count();
        $reported_user_count        = ReportUser::count();
        $live_broadcast_count       = Broadcast::where('status','online')->count();
        $data = array([
            'reported_broadcast_count'  => $reported_broadcast_count,
            'reported_user_count'       =>  $reported_user_count,
            'live_broadcast_count'      =>  $live_broadcast_count
        ]);
        return view('admin.index',compact('data'));
    }
}
