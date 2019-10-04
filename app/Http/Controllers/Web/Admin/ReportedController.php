<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReportBroadcast;
use App\ReportUser;
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
        // dd($reported_broadcasts);
        return view('admin.reported-broadcast',compact('reported_broadcasts'));
    }
    public function reportedUsers(){
        $reported_users = User::with('profile','reportedUser')->paginate('20');
        // dd(count($reported_users[0]['reportedUser']));
        if(isset($reported_users[0]['reportedUser']) && count($reported_users[0]['reportedUser']) > 0){

        }else{
            $reported_users = array();
        }
        return view('admin.reported-users',compact('reported_users'));
    }

    public function reportBroadcastDelete($id){
        ReportBroadcast::find($id)->delete();
        return back()->with('flash_message','Broadcast Deleted Successfully ');
    }
    public function reportBroadcastApproved($id){
        ReportBroadcast::find($id)->delete();
        return back()->with('flash_message','ReportedBroadcast Approved Successfully ');
    }
}
