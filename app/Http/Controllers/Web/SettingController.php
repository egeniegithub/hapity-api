<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use Auth;
use Illuminate\Http\Request;

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
        return view('setting', compact('userinfo'));
    }
    public function is_user_username($username, $user_id)
    {
        dd($username, $user_id);
    }

    public function save_settings(Request $request)
    {

        $user = User::find(Auth::id());

        $rules = [];

        if ($user->username != $request->username) {
            $rules['username'] = 'unique:users,username';
        }

        if ($user->email != $request->email) {
            $rules['email'] = 'unique:users,email';
        }

        $request->validate($rules);

        $profile_picture = $request->file('image');

        //  Saving User Data
        $image_name = $this->handle_profile_picture_upload(Auth::id(), $profile_picture);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();

        $profile = UserProfile::where('user_id', $user->id)->first();
        $profile->full_name = $request->username;
        $profile->email = $request->email;
        $profile->is_sensitive = $request->is_sensitive;

        if (!empty($image_name)) {
            $profile->profile_picture = $image_name;
        }

        $profile->save();

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

}
