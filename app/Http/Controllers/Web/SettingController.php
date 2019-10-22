<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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
        $user_id = $request->user_id;
         $profile_picture = $request->profile_picture;
        $user = User::where('username','<>',$request->username)->where('email','<>',$request->email)->first();
        $profile = UserProfile::where('user_id', $user_id)->first();
        $profile->profile_picture = $this->handle_profile_picture_upload($request,$user_id,$profile_picture);
        // $profile->profile_picture = $profile_picture;

        if(count(collect($user)) > 0){
            $user = User::find(Auth::id());
            $user->username = $request->username;
            $user->email = $request->email;        
            $user->save();
            $profile->full_name = $request->username;
            $profile->email = $request->email;
        }
        
        $profile->is_sensitive = $request->is_sensitive;
        $profile->save();
        return 'true';
        return back()->with("success", "Setting Successfull Update");
    }

    private function handle_profile_picture_upload($request, $user_id, $profile_picture)
    {
        $imageName = '';
        if ($request->hasFile($profile_picture)) {
            $file = $profile_picture;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $imageName = 'profile_picture_' . $user_id . '.' . $extension;
            $path = public_path('images/profile_pictures');
            $file->move($path, $imageName);
        }

        if (!empty($profile_picture) && !is_null($profile_picture)) {
            $imageName = 'profile_picture_' . $user_id . '.jpg';

            $path = public_path('images' . DIRECTORY_SEPARATOR . 'profile_pictures'.DIRECTORY_SEPARATOR);

            if (!is_dir($path)) {
                mkdir($path);
            }

            $base_64_data = $request->input('profile_picture');

            $base_64_data = str_replace('datagea:im/jpeg;base64,', '', $base_64_data);
            $base_64_data = str_replace('data:image/png;base64,', '', $base_64_data);

            File::put($path . $imageName, base64_decode($base_64_data));

            return $imageName;
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
