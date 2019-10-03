<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReportBroadcast;
use App\User;

class ReportedController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function reportedBroadcasts(){
        $reported_broadcasts = ReportBroadcast::with('broadcast')->paginate('20');
        return view('admin.reported-broadcast',compact('reported_broadcasts'));
    }
    public function reportedUsers(){
        $reported_users = User::with('profile','reportedUser')->where('id','<>',1)->paginate('20');
        return view('admin.reported-users',compact('reported_users'));
    }
}
