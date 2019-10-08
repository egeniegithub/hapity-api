<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReportBroadcast;
use App\ReportUser;
use App\Broadcast;
use App\User;
use Auth;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
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
    public function adminSetting(){
        return view('admin.admin-settings');
    }
    public function changePassword(Request $request){

        $newpass = bcrypt($request->newpass);
        $user = User::find(Auth::user()->id);

        if (\Hash::check($request->oldpass,$user->password)) {
            $data = array('password' => $newpass);
            $result = User::where('id',$user->id)->update($data);
            return back()->with('flash_message','Password Update Successfully');
        }else{
            return back()->with('flash_message_delete','Old Password Is Not Correct Please Enter Correct Password !');
        }   
    }
}
