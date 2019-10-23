<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use App\UsersCI;

// use App\Libraries\Wowza_lib;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $wowza = new Wowza_lib();
        // $wowza->get_server_stats();

        $broadcast = Broadcast::orderBy('id', 'DESC')->get()->toArray();
        return view('index')->with('broadcast', $broadcast);
    }

    public function test()
    {
        $this->seed_users_from_old_db();

    }

    private function seed_users_from_old_db()
    {
        echo 'Processing Start <hr /> <pre>'; // Remove This

        $ci_users = UsersCI::with(['broadcasts', 'plugins'])->get();

        foreach ($ci_users as $ci_user) {
            $email_count = User::where('email', $ci_user->email)->count();
            $username_count = User::where('username', $ci_user->username)->count();

            

            if ($email_count <= 0 && $username_count <= 0) {
                $new_user = new User();
                $new_user->name = $ci_user->username;
                $new_user->username = $ci_user->username;
                $new_user->email = !empty($ci_user->email) ? $ci_user->email : $ci_user->username . '@example.com';
                $new_user->password = '';
                $new_user->save();

                $new_user_profile = new UserProfile();
                $new_user_profile->user_id = $new_user->id;
                $new_user_profile->first_name = '';
                $new_user_profile->last_name = '';
                $new_user_profile->email = $new_user->email;
                $new_user_profile->profile_picture = '';
                $new_user_profile->gender = null;
                $new_user_profile->online_status = 'offline';
                $new_user_profile->banned = $ci_user->banned;
                $new_user_profile->date_of_birth = null;
                $new_user_profile->age = 0;
                $new_user_profile->auth_key = $ci_user->auth_key;
                $new_user_profile->full_name = $ci_user->username;
                $new_user_profile->is_sensitive = $ci_user->is_sensitive == 'no' ? 0 : 1;
                $new_user_profile->save();

                echo $new_user->id . ' done' . PHP_EOL;
            }
        }

        echo '</pre>'; //Remove This
    }

    private function save_picture_to_local($picture_url, $picture_path, $file_name_with_ext)
    {
        if (!empty($picture_url)) {
            $picture_content = file_get_contents($picture_url);

            $picture_extension = pathinfo($picture_url, PATHINFO_EXTENSION);

            $picture_extension = !empty($picture_extension) ? $picture_extension : 'jpg';

            file_put_contents(public_path($picture_path . $file_name_with_ext), $picture_content);

        }

        return $file_name_with_ext;
    }

}
