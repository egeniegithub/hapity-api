<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller
{
    public function index(Request $request){
        $data = User::with('profile','broadcasts');
        if($request['search']!='')
        {
            $data = $data->where('username','like','%'.$request['search'].'%');
            // $qry="select username,profile_picture,join_date,sid,email from user where username like '%".$request['search']."%'";
        }
        $users = $data->paginate(20);
        return view('admin.all-users',compact('users'));
    }
}
