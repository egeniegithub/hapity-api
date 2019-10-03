<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use App\ReportUser;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
        $data = User::with('profile','broadcasts')->where('id','<>',1);
        if($request['search']!='')
        {
            $data = $data->where('username','like','%'.$request['search'].'%');
            // $qry="select username,profile_picture,join_date,sid,email from user where username like '%".$request['search']."%'";
        }
        $users = $data->paginate(20);
        return view('admin.all-users',compact('users'));
    }
    public function deleteuser($user_id){
        UserProfile::where('user_id',$user_id)->delete();
        User::find($user_id)->delete();
        return back()->with('flash_message','User Delete Successfull ');
    }
    function approveduser($user_id){
        ReportUser::where('reported_user_id',$user_id)->delete();
        return back()->with('flash_message','User Approve Successfull ');
       
    }
}
