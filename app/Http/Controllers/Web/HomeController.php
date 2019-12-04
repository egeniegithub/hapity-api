<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
use App\PluginId;
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
        $this->seed_users_from_old_db(true, true);
    }

    private function seed_users_from_old_db($pull_profile_pictures = false, $pull_broadcast_pictures = false)
    {
        echo 'Processing Start <hr /> <pre>'; // Remove This

        $ci_users = UsersCI::with(['broadcasts', 'plugins'])->orderBy('sid', 'ASC')->get();

        foreach ($ci_users as $ci_user) {
            $email_count = User::where('email', $ci_user->email)->count();
            $username_count = User::where('username', $ci_user->username)->count();

            if ($email_count <= 0 && $username_count <= 0) {
                $new_user = new User();
                $new_user->name = $ci_user->username;
                $new_user->username = $ci_user->username;
                $new_user->email = !empty($ci_user->email) ? $ci_user->email : $ci_user->username . '@example.com';
                $new_user->password = '';
                $new_user->created_at = $ci_user->join_date;
                $new_user->save();

                $new_user->roles()->attach(HAPITY_USER_ROLE_ID);

                $profile_picture_name = '';
                if ($pull_profile_pictures == true) {
                    $profile_picture_name = $this->fetch_image('profile_picture', $new_user->id, $ci_user->profile_picture, 'profile_pictures');
                }

                $new_user_profile = new UserProfile();
                $new_user_profile->user_id = $new_user->id;
                $new_user_profile->first_name = '';
                $new_user_profile->last_name = '';
                $new_user_profile->email = $new_user->email;
                $new_user_profile->profile_picture = !empty($profile_picture_name) ? $profile_picture_name : '';
                $new_user_profile->gender = null;
                $new_user_profile->online_status = 'offline';
                $new_user_profile->banned = $ci_user->banned;
                $new_user_profile->date_of_birth = null;
                $new_user_profile->age = 0;
                $new_user_profile->auth_key = $ci_user->auth_key;
                $new_user_profile->full_name = $ci_user->username;
                $new_user_profile->is_sensitive = $ci_user->is_sensitive == 'no' ? 0 : 1;
                $new_user_profile->save();

                $ci_user_plugins = $ci_user->plugins()->get();

                foreach ($ci_user_plugins as $ci_user_plugin) {
                    $new_user_plugin = new PluginId();
                    $new_user_plugin->user_id = $new_user->id;
                    $new_user_plugin->url = $ci_user_plugin->url;
                    $new_user_plugin->type = $ci_user_plugin->type;
                    $new_user_plugin->save();
                }

                $ci_user_broadcasts = $ci_user->broadcasts()->get();

                foreach ($ci_user_broadcasts as $ci_user_broadcast) {

                    $new_user_broadcast = new Broadcast();
                    $new_user_broadcast->user_id = $new_user->id;
                    $new_user_broadcast->title = $ci_user_broadcast->title;
                    $new_user_broadcast->description = $ci_user_broadcast->description;
                    $new_user_broadcast->broadcast_image = '';
                    $new_user_broadcast->status = 'offline';
                    $new_user_broadcast->geo_location = $ci_user_broadcast->geo_location;
                    $new_user_broadcast->allow_user_messages = $ci_user_broadcast->allow_user_messages;
                    $new_user_broadcast->stream_url = $ci_user_broadcast->stream_url;
                    $new_user_broadcast->share_url = '';
                    $new_user_broadcast->is_deleted = $ci_user_broadcast->is_deleted;
                    $new_user_broadcast->filename = pathinfo($ci_user_broadcast->stream_url, PATHINFO_BASENAME);
                    $new_user_broadcast->video_name = pathinfo($ci_user_broadcast->stream_url, PATHINFO_BASENAME);
                    $new_user_broadcast->is_sensitive = $ci_user_broadcast->is_sensitive;
                    $new_user_broadcast->post_id = $ci_user_broadcast->post_id;
                    $new_user_broadcast->post_id_joomla = $ci_user_broadcast->post_id_joomla;
                    $new_user_broadcast->post_id_drupal = $ci_user_broadcast->post_id_drupal;
                    $new_user_broadcast->timestamp = $ci_user_broadcast->timestamp;
                    $new_user_broadcast->save();

                    $broadcast_image = '';
                    if ($pull_broadcast_pictures == true) {
                        $broadcast_image = $this->fetch_image('broadcast', $new_user_broadcast->id, $ci_user_broadcast->broadcast_image, 'broadcasts', $new_user->id);
                    }

                    $new_user_broadcast->broadcast_image = $broadcast_image;
                    $new_user_broadcast->save();

                }

                echo $new_user->id . ' done' . PHP_EOL;
            }
        }

        echo '</pre>'; //Remove This
    }

    private function fetch_image($prefix, $id, $image_url, $public_folder, $user_id = 0)
    {
        $picture_name = '';
        if (
            !empty($image_url) &&
            strpos($image_url, 'http') !== false &&
            strpos($image_url, 'null.') === false &&
            strpos($image_url, 'default001.') === false
        ) {
            stream_context_set_default( [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
            
            $headers = get_headers($image_url, 1);

            if (!empty($headers) && isset($headers[0])) {
                if ($headers[0] == 'HTTP/1.1 200 OK') {

                    $picture_extension = pathinfo($image_url, PATHINFO_EXTENSION);
                    $picture_extension = !empty($picture_extension) ? $picture_extension : 'jpg';

                    $picture_name = $prefix . '_' . $id . '.' . $picture_extension;

                    $picture_content = file_get_contents($image_url);

                    if($prefix == 'broadcast' && $user_id > 0) {
                        $path = 'images' . DIRECTORY_SEPARATOR . $public_folder . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR;
                        if(!is_dir($path)) {
                            mkdir($path);
                        }

                        file_put_contents(public_path($path . $picture_name), $picture_content);
                    } else {
                        file_put_contents(public_path('images' . DIRECTORY_SEPARATOR . $public_folder . DIRECTORY_SEPARATOR . $picture_name), $picture_content);
                    }
                }
            }
        }

        return $picture_name;
    }

}
