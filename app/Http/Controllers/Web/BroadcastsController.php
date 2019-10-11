<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\BroadcastViewer;
use App\Http\Controllers\Controller;
use App\PluginId;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use \Validator;

class BroadcastsController extends Controller
{

    private $server;

    public function __construct()
    {
        $this->server = $this->getRandIp();
    }

    public function start_web_cast()
    {
        $data = User::with('profile')->where('id', Auth::id())->first();

        $server = $this->server;

        return view('webcast', compact('data', 'server'));
    }

    public function create_content()
    {
        $data = User::with('profile')->where('id', Auth::id())->first();
        return view('create-content', compact('data'));
    }

    public function startwebbroadcast(Request $request)
    {

        $title = $request->title;
        $description = "";
        $geo_location = $request->geo_location;
        $allow_user_messages = $request->allow_user_messages;
        $user_id = $request->user_id;
        $is_sensitive = $request->is_sensitive;
        $input_server = $request->server_input;

        $post_plugin = $request->post_plugin;
        $broadcast_image = $request->broadcast_image;
        $filename = $request->stream_url;
        if (!$post_plugin) {
            $post_plugin = 'false';
        }

        $stream_url = "rtmp://";
        if ($input_server != '') {
            $server = $input_server;
            $stream_url .= $server;
            $stream_url .= ":1935/live/" . $request->stream_url . '_360p';
        } else {
            $server = $this->$server;
            $stream_url .= $server;
            $stream_url .= ":1935/live/" . $request->stream_url;
        }
        $token = $request->token;
        if (isset(Auth::user()->getToken->token) && Auth::user()->getToken->token != $token) {
            $response = array('status' => 'failure', 'error' => 'Invalid Token');
            echo json_encode($response);
            return;
        }

        $date = date('Y-m-d h:i:s', time());
        if ($geo_location == '' || $user_id == '') {
            $response = array('status' => 'failure', 'error' => 'missing parameter');
            echo json_encode($response);
            return;
        } else {
            if (Auth::check()) {
                if ($allow_user_messages == '') {
                    $allow_user_messages = 'yes';
                }

                $broadcast_data['title'] = $title;
                $broadcast_data['description'] = $description;
                $broadcast_data['geo_location'] = $geo_location;
                $broadcast_data['allow_user_messages'] = $allow_user_messages;
                $broadcast_data['user_id'] = $user_id;
                $broadcast_data['stream_url'] = $stream_url;
                $broadcast_data['created_at'] = $date;
                $broadcast_data['is_sensitive'] = $is_sensitive;
                $broadcast_data['status'] = 'offline';
                $broadcast_data['filename'] = $filename . ".mp4";
                $broadcast_data['video_name'] = '';
                $broadcast_data['broadcast_image'] = $broadcast_image;
                $broadcast_data['share_url'] = '';

                DB::table('broadcasts')->insert($broadcast_data);
                $broadcast_id = DB::getPdo()->lastInsertId();

                $share_url = URL::to('/view-broadcast') . '/' . $broadcast_id;
                $data['share_url'] = $share_url;
                Broadcast::where('id', $broadcast_id)->update($data);
                $response = array(
                    'status' => 'success', 
                    'broadcast_id' => $broadcast_id, 
                    'share_url' => $share_url,
                    'file_name' => $filename
                );

                if ($post_plugin == 'true') {
                    $this->make_plugin_call($broadcast_id, $broadcast_image);
                }

                echo json_encode($response);
                return;
            } else {
                $response = array('status' => 'failure', 'error' => 'user not exist');
                echo json_encode($response);
                return;
            }
        }
    }

    public function update_timestamp_broadcast(Request $request)
    {
        $data['updated_at'] = date('Y-m-d h:i:s', time());
        $broadcast_id = $request->broadcast_id;
        Broadcast::find($broadcast_id)->update($data);
    }

    public function offline_broadcast(Request $request)
    {

        $broadcast_id = $request->broadcast_id;

        $token = $request->token;
        if (isset(Auth::user()->getToken->token) && Auth::user()->getToken->token != $token) {
            $response = array('status' => 'failure', 'error' => 'Invalid Token');
            $result = json_encode($response, true);
            echo $result;
            return;
        }

        if ($broadcast_id) {

            $data['status'] = 'offline';
            Broadcast::find($broadcast_id)->update($data);

            $response = array();
            $response['status'] = 'offline';
            $BroadcastViewer = BroadcastViewer::find($broadcast_id);
            if (isset($BroadcastViewer) && !empty($BroadcastViewer)) {
                BroadcastViewer::find($broadcast_id)->delete();
            }

            $this->make_plugin_call_edit($broadcast_id);
            $result = json_encode($response, true);
            echo $result;
            return;
        } else {
            $response = array('status' => 'failure', 'error' => 'missing parameter');
            $result = json_encode($response, true);
            echo $result;
            return;
        }
    }

    public function make_plugin_call($broadcast_id, $image)
    {
        $broadcast = array();
        $broadcast = Broadcast::leftJoin('users as u', 'u.id', '=', 'broadcasts.user_id')
            ->rightJoin('plugin_ids as pl', 'pl.user_id', '=', 'u.id')
            ->where('broadcasts.id', $broadcast_id)
            ->get();

        if (count($broadcast) > 0) {
            foreach ($broadcast as $data) {
                $title = $data['title'];
                $description = $data['description'];
                $stream_url = $data['stream_url'];
                $status = $data['status'];

                $headers = array(
                    'Content-type: application/xwww-form-urlencoded',
                );
                if ($data['type'] == 'drupal') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $data['auth_key'],
                            'broadcast_image' => $image,
                            'description' => $description,
                            'action' => 'hpb_hp_new_broadcast',
                        )
                    );
                } else if ($data['type'] == 'wordpress') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $data['auth_key'],
                            'broadcast_image' => $image,
                            'description' => $description,
                        )
                    );
                } else if ($data['type'] == 'joomla') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $data['auth_key'],
                            'broadcast_image' => $image,
                            'description' => $description,
                        )
                    );
                }
                $opts = array('http' => array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata,
                ),
                );
                $context = stream_context_create($opts);

                if ($data['type'] == 'wordpress') {
                    $go = $data['url'] . '?action=hpb_hp_new_broadcast';
                } else if ($data['type'] == 'drupal') {
                    $go = $data['url']; // . '?action=hpb_hp_new_broadcast';
                } else if ($data['type'] == 'joomla') {
                    $go = $data['url'] . 'index.php?option=com_hapity&task=savebroadcast.getBroadcastData';
                }

                $result = file_get_contents($go, false, $context);

                if (isset($result) && $result != '') {
                    $data = array();
                    $result = json_decode($result, true);

                    $data['share_url'] = $result['post_url'];

                    $wp_post_id = $result['post_id_wp'];
                    $post_id_joomla = $result['post_id_joomla'];
                    $drupal_post_id = $result['drupal_post_id'];

                    if ($wp_post_id) {
                        $data['post_id'] = $wp_post_id;
                    }
                    if ($post_id_joomla) {
                        $data['post_id_joomla'] = $post_id_joomla;
                    }
                    if ($drupal_post_id) {
                        $data['post_id_drupal'] = $drupal_post_id;
                    }
                    if (!empty($data)) {
                        Broadcast::find($broadcast_id)->update($data);
                    }
                }
            }
        }
    }

    public function make_plugin_call_edit($broadcast_id)
    {
        $broadcast = array();
        $broadcast = Broadcast::leftJoin('users as u', 'u.id', '=', 'broadcasts.user_id')
            ->rightJoin('plugin_ids as pl', 'pl.user_id', '=', 'u.id')
            ->where('broadcasts.id', $broadcast_id)
            ->get();
        if (count($broadcast) > 0) {
            foreach ($broadcast as $data) {
                $title = $data['title'];
                $description = $data['description'];
                $stream_url = str_replace("/live/", "/vod/", $data['stream_url']);
                $image = $data['broadcast_image'];

                $headers = array(
                    'Content-type: application/xwww-form-urlencoded',
                );

                if ($data['type'] == 'drupal') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $data['auth_key'],
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_drupal' => $data['post_id_drupal'],
                        )
                    );
                } else if ($data['type'] == 'wordpress') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $data['auth_key'],
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_wp' => $data['post_id'],
                        )
                    );
                } else if ($data['type'] == 'joomla') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $data['auth_key'],
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_joomla' => $data['post_id_joomla'],
                        )
                    );
                }

                $opts = array('http' => array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata,
                ),
                );

                $context = stream_context_create($opts);

                if ($data['type'] == 'wordpress') {
                    $go = $data['url'] . '?action=hpb_hp_edit_broadcast';
                } else if ($data['type'] == 'drupal') {
                    $go = $data['url'] . '?action=hpb_hp_edit_broadcast';
                } else if ($data['type'] == 'joomla') {
                    $go = $data['url'] . 'index.php?option=com_hapity&task=savebroadcast.editBroadcastData';
                }

                $result = file_get_contents($go, false, $context);
            }
        }
    }

    public function edit_broadcast_content($broadcast_id)
    {
        if (User::find(Auth::user()->id)->exists() && Auth::user()->id != " " && Auth::user()->id != null) {
            $broadcast_data = Broadcast::find($broadcast_id);
            return view('edit-content', compact('broadcast_data'));
        } else {
            return back();
        }

    }

    public function getRandIp()
    {
        if (env('APP_ENV') == 'local') {
            return '72.255.38.246';
        } else {
            $ip = array(0 => '52.18.33.132', 1 => '52.17.132.36');
            $index = rand(0, 1);
            return $ip[$index];
        }
    }

    public function create_content_submission(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'description' => 'required',
            'video' => 'required|mimes:mp4|max:524288',
            'image' => 'required',
        );
        $request->validate($rules);       

        //Handle file upload;
        $video_name_with_ext = '';
        if ($request->hasFile('video')) {
            $video_file = $request->file('video');

            //Generate File name
            $file_name = md5(time()) . '.stream';
            $extension = $video_file->getClientOriginalExtension();
            $video_name_with_ext = $file_name . '.' . $extension;
            $path = base_path('wowza_store');
            $video_file->move($path, $video_name_with_ext);
        }

        //server ip
        $server_ip = $this->getRandIp();

        //stream url
        $stream_url = 'rtmp://' . $server_ip . ':1935/vod/' . $video_name_with_ext;

        $image_file_name_with_ext = '';
        //handle image upload
        if ($request->hasFile('image')) {
            $image_file = $request->file('image');
            $extension = $image_file->getClientOriginalExtension();

            //Generate File name
            $image_file_name_with_ext = md5(time()) . '.' . $extension;
            $path = public_path('images/broadcasts/' . Auth::id() . '/');

            $image_file->move($path, $image_file_name_with_ext);
        }

        $user = Auth::user();
        $user_profile = $user->profile()->get();

        $broadcast = new Broadcast();
        $broadcast->user_id = Auth::id();
        $broadcast->title = $request->title;
        $broadcast->geo_location = $request->geo_location;
        $broadcast->description = $request->description;
        $broadcast->is_sensitive = $request->is_sensitive;
        $broadcast->stream_url = $stream_url;
        $broadcast->broadcast_image = $image_file_name_with_ext;
        $broadcast->filename = $video_name_with_ext;
        $broadcast->status = 'offline';
        $broadcast->share_url = '';
        $broadcast->video_name = $video_name_with_ext;
        $broadcast->save();

        $broadcast->share_url = route('broadcast.view', $broadcast->id);
        $broadcast->save();

        return redirect()->to('dashboard')->with('flash_message', 'Broadcast Uploaded Successfull');
    }

    public function edit_content_submission(Request $request)
    {

        $rules = array(
            'title' => 'required',
            'description' => 'required',
            'video' => 'required|mimes:mp4|max:528000',
        );
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $update_broad = array();
        $stream_urlx = md5(microtime() . rand()) . ".stream";
        $input = $request->all();
        $broadcast_id = $request->bid;
        $update_broad = array();
        $broadcast = Broadcast::find($broadcast_id);
        if (isset($request->title) && !empty($request->title)) {
            $update_broad['title'] = $request->title;
        }
        if (isset($request->description) && !empty($request->description)) {
            $update_broad['description'] = $request->description;
        }
        if ($request->hasFile('video')) {
            $video_file = $request->file('video');
            $info = pathinfo($video_file->getClientOriginalName());
            $ext = $info['extension'];
            $filename = $stream_urlx . "." . $ext;

            $path = base_path('wowza_store');
            $video_path = $video_file->move($path, $filename);
            //Making Stream URL
            $stream_url = "rtmp://";
            $server = $this->getRandIp();
            $stream_url .= $server;
            $stream_url .= ":1935/live/" . $stream_urlx;
            $update_broad['stream_url'] = $stream_url;
            $update_broad['filename'] = $filename;
            $streamURL = Broadcast::where(['id' => $broadcast_id])->first();
            $filename = $streamURL['filename'];
            $file_path = base_path('wowza_store' . DIRECTORY_SEPARATOR . $filename);
            if (is_file($file_path)) {
                unlink($file_path);
            }

        }
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $info = pathinfo($file->getClientOriginalName());
            $ext = $info['extension'];
            $thumbnail_image = Str::random(6) . '_' . now()->timestamp . '.' . $ext;
            $path = public_path('images/broadcasts/' . Auth::user()->id . DIRECTORY_SEPARATOR);

            $file->move($path, $thumbnail_image);
            $update_broad['broadcast_image'] = $thumbnail_image;
            $streamURL = Broadcast::where(['id' => $broadcast_id])->first();
            $filename = $streamURL['broadcast_image'];

            $file_path = public_path('images/broadcasts' . DIRECTORY_SEPARATOR . Auth::user()->id . DIRECTORY_SEPARATOR . $broadcast_id . DIRECTORY_SEPARATOR . $filename);

            if (is_file($file_path)) {
                unlink($file_path);
            }

        }

        Broadcast::find($broadcast_id)->update($update_broad);

        if (isset($input['token']) && !empty($input['token'])) {
            $this->make_plugin_call_edit($broadcast_id, Auth::user()->id);
        }
        return redirect()->to('dashboard')->with('flash_message', 'Broadcast Updated Successfull');
    }

    public function view_broadcast($broadcast_id)
    {
        $filename = '';        
        $broadcast = Broadcast::with(['user'])->find($broadcast_id);

        if (!is_null($broadcast)) {
            return view('view-broadcast', compact('broadcast'));
        } else {
            return back();
        }
    }

    public function update_img_broadcast($broadcast_id, $path)
    {
        $data = array(
            'broadcast_image' => $path,
        );
        $this->db->update('broadcast', $data, "id = $broadcast_id ");
        return $path;
    }

    public function make_plugin_call_upload($bid, $uid)
    {

        $share_url = "";
        $broadcast_id = $bid;
        $broadcast = Broadcast::find($bid);
        $user = User::find($uid);
        $plugins = PluginId::where(['user_id' => $uid]);
        if (is_array($plugins) && count($plugins) > 0) {
            foreach ($plugins as $data) {
                $title = $broadcast['title'];
                $share_url = $broadcast['share_url'];
                $description = $broadcast['description'];
                $stream_url = str_replace("/live/", "/vod/", $broadcast['stream_url']);

                if ($data['type'] == 'drupal') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $user['auth_key'],
                            'broadcast_image' => $broadcast['broadcast_image'],
                            'description' => $description,
                            'action' => 'hpb_hp_new_broadcast',
                        )
                    );
                } else if ($data['type'] == 'wordpress') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $user['auth_key'],
                            'broadcast_image' => $broadcast['broadcast_image'],
                            'description' => $description,
                        )
                    );
                } else if ($data['type'] == 'joomla') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $user['auth_key'],
                            'broadcast_image' => $broadcast['broadcast_image'],
                            'description' => $description,
                        )
                    );
                }
                $opts = array('http' => array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata,
                ),
                );

                $context = stream_context_create($opts);

                if ($data['type'] == 'wordpress') {
                    $go = $data['url'] . '?action=hpb_hp_new_broadcast';
                } else if ($data['type'] == 'drupal') {
                    $go = $data['url'];
                } else if ($data['type'] == 'joomla') {
                    $go = $data['url'] . 'index.php?option=com_hapity&task=savebroadcast.getBroadcastData';
                }
                $result = file_get_contents($go, false, $context);

                if (isset($result) && $result != '') {
                    $update_broadcast = Broadcast::find($bid);
                    $flag = 0;
                    $result = json_decode($result, true);

                    $update_broadcast->share_url = $result['post_url'];

                    $share_url = $result['post_url'];

                    $wp_post_id = $result['post_id_wp'];
                    $post_id_joomla = $result['post_id_joomla'];
                    $drupal_post_id = $result['drupal_post_id'];

                    if ($wp_post_id) {
                        $update_broadcast->post_id = $wp_post_id;
                        $flag = 1;
                    }
                    if ($post_id_joomla) {
                        $update_broadcast->post_id_joomla = $post_id_joomla;
                        $flag = 1;
                    }
                    if ($drupal_post_id) {
                        $update_broadcast->post_id_drupal = $drupal_post_id;
                        $flag = 1;
                    }
                    if ($flag) {
                        $update_broadcast->save();
                    }
                }
            }
        }

        return $share_url;
    }

    public function get_name_from_link($link)
    {
        $name = "";
        $token = strtok($link, '/');
        while ($token !== false) {
            $name = $token;
            $token = strtok('/');
        }

        return $name;
    }

    //params - token, user_id, stream_id, stream_url
    public function deleteBroadcast(Request $request)
    {
        $user_id = $request->user_id;
        $stream_id = $request->stream_id;

        $streamURL = Broadcast::where(['id' => $stream_id])->first();
        $filename = $streamURL['filename'];
        $broadcast_image = $streamURL['broadcast_image'];
        Broadcast::where('user_id', $user_id)->where(['id' => $stream_id])->delete();
        $file_path = base_path('wowza_store' . DIRECTORY_SEPARATOR . $filename);
        if (is_file($file_path)) {
            unlink($file_path);
        }

        $file_image_path = public_path('images/broadcasts' . DIRECTORY_SEPARATOR . Auth::user()->id . DIRECTORY_SEPARATOR . $stream_id . DIRECTORY_SEPARATOR . $broadcast_image);
        if (is_file($file_image_path)) {
            unlink($file_image_path);
        }

        $response['status'] = "success";
        $response['response'] = "deletebroadcast";
        $response['message'] = "deleted successfully";

        return response()->json($response);

    }

    public function update_view_count(Request $request, $id) {
        $count = 0; 
        if($id > 0) {
            $broadcast = Broadcast::find($id);

            $count = $broadcast->view_count + 1;

            $broadcast->view_count = $count;
            $broadcast->save();  
        
        }

        return response()->json(['view_count' => $count]);
    }
}
