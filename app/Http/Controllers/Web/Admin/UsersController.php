<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use App\ReportUser;
use Exception;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
        $data = User::with('profile','broadcasts');

        $reported_users = ReportUser::all();

        $reported_user_ids = [];
        foreach($reported_users as $reported_user) {
            $reported_user_ids[] = $reported_user->reported_user_id;
        }

        if($request['search']!='')
        {
            $data = $data->where('username','like','%'.$request['search'].'%')->orWhere('email','like','%'.$request['search'].'%');
        }
        $users = $data->orderBy('users.id','DESC')->paginate(20);
        return view('admin.all-users',compact('users', 'reported_user_ids'));
    }
    public function deleteuser(Request $request){
        $user_id = $request->user_id;
        try {
            UserProfile::where('user_id',$user_id)->delete();
            User::find($user_id)->delete();
            
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
 
        return back()->with('flash_message','User Delete Successfull ');
    }
    public function approveduser(Request $request){
        $user_id = $request->user_id;
        try {
            ReportUser::where('reported_user_id',$user_id)->delete();
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
        
        return back()->with('flash_message','User Approve Successfull ');
       
    }
}
