<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\BroadcastViewer;
use App\Http\Controllers\Controller;
use App\Http\Helpers\PluginFunctions;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
                $broadcast = new Broadcast();
                $broadcast->title = $title;
                $broadcast->description = $description;
                $broadcast->geo_location = $geo_location;
                $broadcast->allow_user_messages = $allow_user_messages;
                $broadcast->user_id = $user_id;
                $broadcast->stream_url = $stream_url;
                $broadcast->created_at = $date;
                $broadcast->is_sensitive = $is_sensitive;
                $broadcast->status = 'offline';
                $broadcast->filename = $filename . ".mp4";
                $broadcast->video_name = '';
                $broadcast->broadcast_image = '';
                $broadcast->share_url = '';
                $broadcast->resolution = '720p';
                $broadcast->save();
                $broadcast_id = $broadcast->id;

                $broadcast->broadcast_image = $this->handle_image_file_upload($request, $broadcast_id, $user_id);
                $share_url = route('broadcast.view', $broadcast_id);
                $broadcast->share_url = $share_url;
                $broadcast->save();
                $response = array(
                    'status' => 'success',
                    'broadcast_id' => $broadcast_id,
                    'share_url' => $share_url,
                    'file_name' => $filename,
                );

                if ($post_plugin == 'true') {
                    $plugin = new PluginFunctions();
                    $plugin->make_plugin_call($broadcast_id, $broadcast_image);
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
            $plugin = new PluginFunctions();
            $plugin->make_plugin_call_edit($broadcast_id);
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
            return $ip[0];
        }
    }

    public function create_content_submission(Request $request)
    {

        $post_plugin = true;

        $rules = array(
            'title' => 'required',
            'video' => 'max:524288',
        );
        $request->validate($rules);

        $output_file_name = '';

        //Handle file upload;
        $video_name_with_ext = '';
        if ($request->hasFile('video')) {
            $video_file = $request->file('video');
            $video_original_name = $video_file->getClientOriginalName();
            $ext = $video_file->getClientOriginalExtension();

            $temp_path = storage_path('temp');

            $file_name = md5(time()) . ".stream." . $ext;
            $wowza_path = base_path('wowza_store');

            $output_file_name = md5(time()) . ".stream.mp4";

            $video_path = $video_file->move($temp_path, $file_name);

            copy($temp_path . DIRECTORY_SEPARATOR . $file_name, $wowza_path . DIRECTORY_SEPARATOR . $output_file_name);

            ffmpeg_upload_file_path($video_path->getRealPath(), $wowza_path . DIRECTORY_SEPARATOR . $output_file_name);
        }

        //server ip
        $server_ip = $this->getRandIp();

        //stream url
        $stream_url = !empty($output_file_name) ? 'rtmp://' . $server_ip . ':1935/vod/' . $output_file_name : '';

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
        $broadcast->filename = $output_file_name;
        $broadcast->status = 'offline';
        $broadcast->share_url = '';
        $broadcast->video_name = $output_file_name;
        $broadcast->resolution = '720p';
        $broadcast->save();
        $plugin = new PluginFunctions();
        $result = $plugin->make_plugin_call_upload($broadcast->id);
        if (!empty($result)) {
            $broadcast->share_url = $result;
        } else {
            $broadcast->share_url = route('broadcast.view', $broadcast->id);
        }
        $broadcast->save();
        return redirect()->to('dashboard')->with('flash_message', 'Broadcast Uploaded Successfull');
    }

    public function edit_content_submission(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'video' => 'max:524288',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $update_broad = array();
        $stream_url = md5(microtime() . rand()) . ".stream";
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
            $video_original_name = $video_file->getClientOriginalName();
            $ext = $video_file->getClientOriginalExtension();

            $temp_path = storage_path('temp');

            $file_name = md5(time()) . ".stream." . $ext;
            $wowza_path = base_path('wowza_store');

            $output_file_name = md5(time()) . ".stream.mp4";

            $video_path = $video_file->move($temp_path, $file_name);

            copy($temp_path . DIRECTORY_SEPARATOR . $file_name, $wowza_path . DIRECTORY_SEPARATOR . $output_file_name);

            ffmpeg_upload_file_path($video_path->getRealPath(), $wowza_path . DIRECTORY_SEPARATOR . $output_file_name);

            $broadcast = Broadcast::find($broadcast_id);

            $old_video_file_name = $broadcast->video_name;

            if (is_file($wowza_path . DIRECTORY_SEPARATOR . $old_video_file_name)) {
                unlink($wowza_path . DIRECTORY_SEPARATOR . $old_video_file_name);
            }

            //server ip
            $server_ip = $this->getRandIp();

            //stream url
            $stream_url = 'rtmp://' . $server_ip . ':1935/vod/' . $output_file_name;

            $broadcast->video_name = $output_file_name;
            $broadcast->stream_url = $stream_url;
            $broadcast->filename = $output_file_name;
            $broadcast->save();

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
            $plugin = new PluginFunctions();
            $plugin->make_plugin_call_edit($broadcast_id);
        }
        return redirect()->to('dashboard')->with('flash_message', 'Broadcast Updated Successfull');
    }

    public function view_broadcast($broadcast_id)
    {
        $filename = '';
        $broadcast = Broadcast::with(['user'])->find($broadcast_id);

        if (!is_null($broadcast)) {
            if (isset($broadcast->broadcast_image) && ($broadcast->broadcast_image != '')) {
                $broadcast['broadcast_image'] = asset('images/broadcasts/' . $broadcast->user_id . '/' . $broadcast->broadcast_image);
            } else {
                $broadcast['broadcast_image'] = getBroadcastThumbnail($broadcast);
            }
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
        Broadcast::where('id', $broadcast_id)->update($data);
        return $path;
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

        $plugin = new PluginFunctions();
        $plugin->make_plugin_call_delete($stream_id);

        Broadcast::where('user_id', $user_id)->where(['id' => $stream_id])->delete();
        $file_path = base_path('wowza_store' . DIRECTORY_SEPARATOR . $filename);
        if (is_file($file_path)) {
            unlink($file_path);
        }

        $file_image_path = public_path('images/broadcasts' . DIRECTORY_SEPARATOR . Auth::user()->id . DIRECTORY_SEPARATOR . $broadcast_image);
        if (is_file($file_image_path)) {
            unlink($file_image_path);
        }

        $response['status'] = "success";
        $response['response'] = "deletebroadcast";
        $response['message'] = "deleted successfully";

        return response()->json($response);

    }

    public function update_view_count(Request $request, $id)
    {
        $count = 0;
        if ($id > 0) {
            $broadcast = Broadcast::find($id);

            $count = $broadcast->view_count + 1;

            $broadcast->view_count = $count;
            $broadcast->save();

        }

        return response()->json(['view_count' => $count]);
    }

    private function handle_image_file_upload($request, $broadcast_id, $user_id)
    {
        $image = $request->input('broadcast_image');

        $thumbnail_image = '';
        if ($request->hasFile('broadcast_image')) {
            $file = $request->file('broadcast_image');
            $ext = $file->getClientOriginalExtension();
            $thumbnail_image = md5(time()) . '.' . $ext;
            $path = public_path('images' . DIRECTORY_SEPARATOR . 'broadcasts' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR);

            if (!is_dir($path)) {
                mkdir($path);
            }

            $file->move($path, $thumbnail_image);

            return $thumbnail_image;
        }

        if (!empty($image) && !is_null($image)) {
            $thumbnail_image = md5(time()) . '.jpg';

            $path = public_path('images' . DIRECTORY_SEPARATOR . 'broadcasts' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR);

            if (!is_dir($path)) {
                mkdir($path);
            }

            $base_64_data = $request->input('broadcast_image');

            $base_64_data = str_replace('datagea:im/jpeg;base64,', '', $base_64_data);
            $base_64_data = str_replace('data:image/png;base64,', '', $base_64_data);

            File::put($path . $thumbnail_image, base64_decode($base_64_data));

            return $thumbnail_image;
        }

        return $thumbnail_image;
    }

    public function handle_old_view_broadcast($broadcast_id){
        $url = 'https://www.hapity.com/main/view_broadcast/'.$broadcast_id;
        $broadcast = Broadcast::where('share_url',$url)->first();

        if (isset($broadcast->id) && $broadcast->id > 0) {
            return redirect()->route('broadcast.view',$broadcast->id);
        } else {
            return redirect()->route('home');
        }
    }
}
