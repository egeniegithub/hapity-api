<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth','cors']);
    }

    public function settings()
    {
        $userinfo = User::with('profile')->where('id', Auth::id())->first()->toArray();
        return view('setting', compact('userinfo'));
    }
    
    public function save_settings(Request $request)
    {

        $user = User::find(Auth::id());
        $user->username = $request->username;
        $user->email = $request->email;        
        $user->save();
        $profile = UserProfile::where('user_id', $user->id)->first();
        $profile->full_name = $request->username;
        $profile->email = $request->email;
        $profile->is_sensitive = $request->is_sensitive;
        $profile->profile_picture = $request->profile_picture;
        $profile->save();
        return 'true';
        return back()->with("success", "Setting Successfull Update");
    }

    private function handle_profile_picture_upload($user_id, $profile_picture)
    {
        $imageName = '';
        if (!empty($profile_picture)) {
            $file = $profile_picture;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $imageName = 'profile_picture_' . $user_id . '.' . $extension;
            $path = public_path('images/profile_pictures');
            $file->move($path, $imageName);
        }

        return $imageName;
    }

    public function check_username(Request $request){
        $username = $request->username;
        $user_id = $request->user_id;
        $user = [];
        $user = User::where('id',$user_id)->where('username',$username)->first();

        if(isset($user) && !empty($user) && count(collect($user)) > 0){
            echo "true";
        }else{
            echo "false";
        }
    }
    public function check_email(Request $request){
        $email = $request->email;
        $user_id = $request->user_id;
        $user = [];
        $user = User::where('id',$user_id)->where('email',$email)->first();
        if(isset($user) && !empty($user) && count(collect($user)) > 0){
            echo "true";
        }else{
            echo "false";
        }
    }

}
