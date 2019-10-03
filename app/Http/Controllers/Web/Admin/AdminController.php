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
        $old = $request->oldpass;
        $new = $request->newpass;
        $oldpass = md5($old);
        $newpass = md5($new);
        $user = User::find(Auth::user()->id)->where('password',$oldpass)->count();
        if($user > 0){
            User::find(Auth::user()->id)->where('id',Auth::user()->id)->where('password',$oldpass)->update(['password',$newpass]);
            return back()->with('flash_message','Password Update Successfully');
        }else{
            return back()->with('flash_message_delete','Password Not Match Please Enter Correct Password !');
        }
        dd($user);
        $result=$this->db->query($qry);
        if($result->num_rows()>0){
            return $result->row()->id;
        }else{
            return 'not-match';
        }

        $qry="update admin set password = '".$new."' where username = '".$this->session->userdata('admin_username')."'";

        
    }
}
