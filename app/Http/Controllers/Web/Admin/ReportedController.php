<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\ReportBroadcast;
use App\User;
use Exception;
use Illuminate\Http\Request;

class ReportedController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function reportedBroadcasts()
    {
        $reported_broadcasts = ReportBroadcast::with('broadcast')->paginate('20');
        return view('admin.reported-broadcast', compact('reported_broadcasts'));
    }
    public function reportedUsers()
    {
        $reported_users = User::with('profile', 'reportedUser')->paginate('20');
        if (isset($reported_users[0]['reportedUser']) && count($reported_users[0]['reportedUser']) > 0) {

        } else {
            $reported_users = array();
        }
        return view('admin.reported-users', compact('reported_users'));
    }

    public function reportBroadcastDelete(Request $request)
    {
        $id = $request->broadcast_id;
        try {
            ReportBroadcast::find($id)->delete();
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return back()->with('flash_message', 'Broadcast Deleted Successfully ');
    }
    public function reportBroadcastApproved(Request $request)
    {
        $id = $request->broadcast_id;
        try {
            ReportBroadcast::find($id)->delete();
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return back()->with('flash_message', 'ReportedBroadcast Approved Successfully ');
    }
}
