<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
Use Redirect;
use DB;

class SettingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function settings()
	{
        $userinfo = User::with('profile')->where('id', Auth::id())->first()->toArray();
    	return view('setting',compact('userinfo'));     
	}
    public function is_user_username($username, $user_id){
        dd($username,$user_id);
    }
    public function save_settings(Request $request){
        $user_id = $request->user_id;
        $rules = array(
            'email' => 'unique:users,email,' . $user_id,
            'username' => 'unique:users,username,' . $user_id,
        );
      
        // $validator = Validator::make($request->all(), $rules);
        $validator = Validator::make( $request->all(), $rules);
        if($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
   
        $username = $request->username;
        $email = $request->email;
        $is_sensitive = $request->is_sensitive;
        $profile_picture = $request->file('image');
        //  Saving User Data
      
        $imageName = $this->handle_base_64_profile_picture($user_id, $profile_picture);
        $user['username'] = $request->username;
        $user['email']    = $request->email;
        
        $profile['full_name'] = $request->username;
        $profile['email']    = $request->email;
        $profile['is_sensitive'] = $request->is_sensitive;
        $profile['profile_picture']   = $imageName;
        DB::table('users')->where('id',$user_id)->update($user);
        DB::table('user_profiles')->where('user_id',$user_id)->update($profile);
        return back()->with("success","Setting Successfull Update");
    }

    private function handle_base_64_profile_picture($user_id, $profile_picture)
    {
        $imageName = '';
        if (!empty($profile_picture)) {
              $file = $profile_picture;
              $extension = $file->getClientOriginalExtension(); // getting image extension
              $filename =time().'.'.$extension;
              $imageName = 'profile_picture_' . $user_id . '.' . $extension;
              $path = public_path('profile_pictures');
              $file->move($path, $imageName);
            // File::put(public_path('images' . DIRECTORY_SEPARATOR . 'profile_pictures' . DIRECTORY_SEPARATOR . $imageName), base64_decode($image));
        }

        return $imageName;
    }

}
