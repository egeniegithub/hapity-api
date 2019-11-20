<?php

namespace App\Http\Controllers;

use App\Broadcast;
use App\Http\Controllers\Controller;
use App\Http\Helpers\PluginFunctions;
use App\PluginId;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use \Validator;

class BroadcastsController extends Controller
{
    public function __construct()
    {
        auth()->setDefaultDriver('api');
//        $this->middleware('auth:api', ['except' => ['uploadBroadcast', 'editBroadcast', 'deleteBroadcast']]);
    }

    public function upload(Request $request)
    {
        $rules = array(
            'user_id' => 'required',
        );

        $messages = array(
            'user_id.required' => 'User ID is required.',
            'video' => 'size:524288|mimes:mp4',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0],
            );
            return response()->json($response);
        }

        $user = User::find($request->input('user_id'));

        $broadcast = new Broadcast();

        $broadcast->user_id = $request->input('user_id');
        $broadcast->title = !is_null($request->input('title')) ? $request->input('title') : '';
        $broadcast->geo_location = !is_null($request->input('geo_location')) ? $request->input('geo_location') : '';
        $broadcast->description = !is_null($request->input('description')) ? $request->input('description') : '';
        $broadcast->is_sensitive = !is_null($request->input('is_sensitive')) ? $request->input('is_sensitive') : '';
        $broadcast->stream_url = '';
        $broadcast->share_url = '';
        $broadcast->video_name = '';
        $broadcast->status = 'offline';
        $broadcast->save();

        $broadcast->share_url = route('broadcast.view', $broadcast->id);
        $broadcast->save();

        $stream_video_info = $this->handle_video_file_upload($request);

        if (!empty($stream_video_info)) {
            $broadcast->stream_url = $stream_video_info['file_stream_url'];
            $broadcast->filename = $stream_video_info['file_name'];
            $broadcast->video_name = $stream_video_info['file_name'];
            $broadcast->save();
        }

        $stream_image_name = $this->handle_image_file_upload($request, $broadcast->id, $request->input('user_id'));

        if (!empty($stream_image_name)) {
            $broadcast->broadcast_image = $stream_image_name;
            $broadcast->save();
        }

        $broadcast->share_url = route('broadcast.view', $broadcast->id);
        $broadcast->save();

        $response = [];
        $response['status'] = 'success';
        $response['broadcast_id'] = $broadcast->id;
        $response['share_url'] = $broadcast->share_url;
        $response['stream_url'] = $broadcast->stream_url;
        $response['video'] = $broadcast->video_name;
        if ($broadcast->broadcast_image) {
            $response['image'] = asset('images/broadcasts/' . $request->input('user_id') . '/' . $broadcast->broadcast_image);
        } else {
            $response['image'] = asset('images/default-image-mobile.png');
        }

        if (!empty($stream_video_info) && isset($stream_video_info['file_server'])) {
            $response['server'] = $stream_video_info['file_server'];
        }

        $response['response'] = 'uploadbroadcast';

        if (boolval($request->input('post_plugin'))) {
            //TODO debug this
            $plugin = new PluginFunctions();
            $result = $plugin->make_plugin_call_upload($broadcast->id);

        }

        return response()->json(['response' => $response]);
    }

    public function start(Request $request)
    {
        $rules = array(
            'user_id' => 'required',
        );
        $messages = array(
            'user_id.required' => 'User ID is required.',
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0],
            );
            return response()->json($response);
        }

        $server = $this->getRandIp();
        $stream_url = $this->make_streaming_server_url($server, $request->input('stream_url'), true);

        $user = User::find($request->input('user_id'));

        $broadcast = new Broadcast();
        $broadcast->user_id = $request->input('user_id');
        $broadcast->title = !is_null($request->input('title')) ? $request->input('title') : '';
        $broadcast->geo_location = !is_null($request->input('geo_location')) ? $request->input('geo_location') : '';
        $broadcast->description = !is_null($request->input('description')) ? $request->input('description') : '';
        $broadcast->is_sensitive = !is_null($request->input('is_sensitive')) ? $request->input('is_sensitive') : '';
        $broadcast->stream_url = $stream_url;
        $broadcast->share_url = '';
        $broadcast->video_name = $request->input('stream_url');
        $broadcast->filename = $request->input('stream_url') . '.mp4';
        $broadcast->status = 'online';
        $broadcast->save();

        $broadcast->share_url = route('broadcast.view', $broadcast->id);
        $broadcast->save();

        $stream_image_name = $this->handle_image_file_upload($request, $broadcast->id, $request->input('user_id'));

        if (!empty($stream_image_name)) {
            $broadcast->broadcast_image = $stream_image_name;
            $broadcast->save();
        }

        //$broadcast->share_url = route('view_broadcast', $broadcast->id);
        $broadcast->save();

        $response = [];
        $response['status'] = 'success';
        $response['broadcast_id'] = $broadcast->id;
        $response['share_url'] = $broadcast->share_url;
        $response['stream_url'] = $broadcast->stream_url;
        $response['video'] = $broadcast->video_name;
        if ($broadcast->broadcast_image) {
            $response['image'] = asset('images/broadcasts/' . $request->input('user_id') . '/' . $broadcast->broadcast_image);
        } else {
            $response['image'] = asset('images/default-image-mobile.png');
        }
        $response['server'] = $server;
        $response['response'] = 'startbroadcast';

        if (boolval($request->input('post_plugin'))) {
            //TODO debug this
            $plugin = new PluginFunctions();
            $share_url = $plugin->make_plugin_call_upload($broadcast->id);
        }

        return response()->json(['response' => $response]);
    }

    public function edit(Request $request)
    {
        $rules = array(
            'broadcast_id' => 'required',
        );

        $messages = array(
            'broadcast_id.required' => 'Broadcast ID is required.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0],
            );
            return response()->json($response);
        }

        $broadcast_id = $request->input('broadcast_id');
        $title = $request->input('title');
        $geo_location = $request->input('geo_location');
        $description = $request->input('description');
        $is_sensitive = $request->input('is_sensitive');
        $status = $request->input('status');

        $broadcast = Broadcast::find($broadcast_id);

        if (!is_null($title) && !empty($title)) {
            $broadcast->title = $title;
            $broadcast->save();
        }

        if (!is_null($geo_location) && !empty($geo_location)) {
            $broadcast->geo_location = $geo_location;
            $broadcast->save();
        }

        if (!is_null($description) && !empty($description)) {
            $broadcast->description = $description;
            $broadcast->save();
        }

        if (!is_null($is_sensitive) && !empty($is_sensitive)) {
            $broadcast->is_sensitive = $is_sensitive;
            $broadcast->save();
        }

        if (!is_null($status) && !empty($request->input('status'))) {
            $broadcast->status = $request->input('status');
            $broadcast->save();
        }

        $broadcast->save();

        $stream_video_info = $this->handle_video_file_upload($request);
        if (!empty($stream_video_info)) {
            $file_path = base_path('wowza_store' . DIRECTORY_SEPARATOR . $broadcast->filename);
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            if ($request->has('video')) {
                $broadcast->stream_url = $stream_video_info['file_stream_url'];
                $broadcast->filename = $stream_video_info['file_name'];
                $broadcast->video_name = $stream_video_info['file_original_name'];
                $broadcast->save();
            }
        }

        $stream_image_name = $this->handle_image_file_upload($request, $broadcast->id, $broadcast->user_id);

        if (!empty($stream_image_name)) {
            $broadcast->broadcast_image = $stream_image_name;
            $broadcast->save();
        }

        $broadcast->share_url = route('broadcast.view', $broadcast->id);
        $broadcast->save();

        $response = [];
        $response['status'] = 'success';
        $response['broadcast_id'] = $broadcast->id;
        $response['share_url'] = $broadcast->share_url;
        $response['stream_url'] = $broadcast->stream_url;
        $response['video'] = $broadcast->video_name;
        if (!empty($broadcast->broadcast_image)) {
            $response['image'] = asset('images/broadcasts/' . $broadcast->user_id . '/' . $broadcast->broadcast_image);
        } else {
            $response['image'] = asset('images/default-image-mobile.png');
        }

        if (!empty($stream_video_info)) {
            $response['server'] = $stream_video_info['file_server'];
        }
        $response['response'] = 'editbroadcast';

        if (boolval($request->input('post_plugin'))) {
            //TODO debug this
            $plugin = new PluginFunctions();
            $share_url = $plugin->make_plugin_call_edit($broadcast_id);
        }

        return response()->json(['response' => $response]);
    }

    //params - token, user_id, stream_id, stream_url
    public function delete(Request $request)
    {
        $input = $request->all();

        $rules = array(
            'user_id' => 'required',
            'broadcast_id' => 'required',
        );
        $messages = array(
            'user_id.required' => 'User ID is required.',
            'broadcast_id.required' => 'Stream ID is required.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json($messages);
        }
        $streamURL = Broadcast::where(['id' => $input['broadcast_id']])->first();

        if (!is_null($streamURL)) {
            $streamURL = $streamURL->toArray();
            $file_name = $streamURL['filename'];
            Broadcast::where('user_id', $input['user_id'])->where(['id' => $input['broadcast_id']])->delete();

            $file_path = base_path('wowza_store' . DIRECTORY_SEPARATOR . $file_name);
            if (file_exists($file_path)) {
                exec('rm -f ' . $file_path);
            }

            $response['status'] = "success";
            $response['response'] = "deletebroadcast";
            $response['message'] = "deleted successfully";

            return response()->json($response);
        } else {
            $response['status'] = "error";
            $response['response'] = "deletebroadcast";
            $response['message'] = "Broadcast not Found!";

            return response()->json($response);
        }

    }

    public function all_user_broadcasts(Request $request)
    {
        $rules = array(
            'user_id' => 'required',
        );

        $messages = array(
            'user_id.required' => 'User ID is required.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json($messages);
        }

        $allUserBroadcast = Broadcast::orderBy('id', 'desc')->where('user_id', $request->input('user_id'))->get();

        $user = User::with('profile')->find($request->input('user_id'))->toArray();

        $broadcasts = [];

        $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';
        $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';

        foreach ($allUserBroadcast as $key => $broadcast) {

            $file_info = !empty($broadcast->filename) ? pathinfo($broadcast->filename) : [];

            $ext = !empty($file_info) ? $file_info['extension'] : 'mp4';

            $stream_url = !empty($broadcast->filename) ? 'http://52.18.33.132:1935/' . $vod_app . '/' . $ext . ':' . $broadcast->filename . '/playlist.m3u8' : '';
            if ($broadcast->status == 'online') {
                $stream_url = !empty($broadcast->filename) ? 'https://media.hapity.com/' . $live_app . '/' . $broadcast->filename . '/playlist.m3u8' : '';
            }

            $broadcastObj = [];
            $broadcastObj['id'] = $broadcast->id;
            $broadcastObj['geo_location'] = $broadcast->geo_location;
            $broadcastObj['filename'] = $broadcast->filename;
            $broadcastObj['title'] = $broadcast->title;
            $broadcastObj['description'] = $broadcast->description;
            $broadcastObj['is_sensitive'] = $broadcast->is_sensitive;
            $broadcastObj['stream_url'] = $stream_url;
            $broadcastObj['status'] = $broadcast->status;
            $broadcastObj['broadcast_image'] = !empty($broadcast->broadcast_image) ? asset('images/broadcasts/' . $broadcast->user_id . '/' . $broadcast->broadcast_image) : asset('images/default-image-mobile.png');
            $broadcastObj['share_url'] = !empty($broadcast->share_url) ? $broadcast->share_url : route('broadcast.view', $broadcast->id);
            $broadcastObj['username'] = $user['username'];
            $broadcastObj['user_id'] = $user['id'];
            $broadcastObj['profile_picture'] = !empty($user['profile']['profile_picture']) ? asset('images/profile_pictuers/' . $user['profile']['profile_picture']) : '';

            $broadcasts[] = $broadcastObj;
        }

        $response = [];
        $response['status'] = 'success';
        $response['user_id'] = $request->input('user_id');
        $response['broadcasts'] = $broadcasts;

        return response()->json($response);

    }

    public function update_timestamp(Request $request)
    {
        $rules = array(
            'broadcast_id' => 'required',
        );
        $messages = array(
            'broadcast_id.required' => 'Broadcast video is required.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0],
            );
            return response()->json($response);
        }

        $input = $request->all();
        $broadcast_id = $input['broadcast_id'];
        $date = date('Y-m-d h:i:s', time());

        $broadcast = Broadcast::find($broadcast_id);
        $broadcast->timestamp = $date;
        $broadcast->status = 'online';
        $broadcast->save();

        $response = array();
        $response = array();
        $response['status'] = 'success';
        $response['timestamp'] = $date;
        $response['message'] = 'Broadcast Timestamp Successfully Updated';

        return response($response, 200);
    }

    public function stop_broadcast(Request $request)
    {
        $rules = array(
            'broadcast_id' => 'required',
        );
        $messages = array(
            'broadcast_id.required' => 'Broadcast video is required.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0],
            );
            return response()->json($response);
        }

        $input = $request->all();
        $broadcast_id = $input['broadcast_id'];
        $date = date('Y-m-d h:i:s', time());

        $broadcast = Broadcast::find($broadcast_id);

        if (!is_null($broadcast)) {
            $broadcast->timestamp = $date;
            $broadcast->status = 'offline';
            $broadcast->save();

            $response = array();
            $response['status'] = 'success';
            $response['timestamp'] = $date;
            $response['message'] = 'Broadcast Successfully Stopped';

            return response($response, 200);
        } else {
            $response = array();
            $response['status'] = 'error';
            $response['timestamp'] = $date;
            $response['message'] = 'Broadcast Not Found';

        }

    }

    private function getRandIp()
    {
        if (env('APP_ENV') == 'local') {
            return '72.255.38.246';
        } else {
            $ip = array(0 => '52.18.33.132', 1 => '52.17.132.36');
            $index = rand(0, 1);
            return $ip[$index];
        }
    }

   

    private function make_streaming_server_url($server, $file_name, $live = false)
    {

        //Making Stream URL
        $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';
        $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';

        $protocol = $live ? 'rtmp:' : 'http:';

        $file_info = pathinfo($file_name);
        $ext = !empty($file_info) ? $file_info['extension'] : 'mp4';

        $ext = $ext == 'stream' ? 'mp4' : $ext;

        if ($live == true) {
            $stream_url = $protocol . "//media.hapity.com:1935/" . $live_app . "/" . $file_name . '/playlist.m3u8';
        } else {
            $stream_url = $protocol . "//media.hapity.com:1935/" . $vod_app . "/" . $ext . ':' . $file_name . '/playlist.m3u8';
        }

        return $stream_url;

    }

    private function handle_video_file_upload($request)
    {
        $to_return = [];
        if ($request->hasFile('video')) {

            $video_file = $request->file('video');
            $video_original_name = $video_file->getClientOriginalName();
            $ext = $video_file->getClientOriginalExtension();

            $file_name = md5(time()) . ".stream." . $ext;
            $path = base_path('wowza_store');

            $video_path = $video_file->move($path, $file_name);

            $server = $this->getRandIp();

            $stream_url = $this->make_streaming_server_url($server, $file_name, false);

            $to_return = [
                'file_original_name' => $video_original_name,
                'file_name' => $file_name,
                'file_path' => $video_path,
                'file_stream_url' => $stream_url,
                'file_server' => $server,
            ];

            if (env('APP_ENV') != 'local') {
                
                ffmpeg_upload_file_path($video_path->getRealPath());
                //TODO to be debuged
                /*
            $temp_pathtosave = "/home/san/live/temp-" . $filename;
            $pathtosave = "/home/san/live/" . $filename;

            $shell_exec = shell_exec("ffprobe -loglevel error -select_streams v:0 -show_entries stream_tags=rotate -of default=nw=1:nk=1 $path.$filename");

            if ($shell_exec == 90) {
            shell_exec('rm ' . $path . $filename);
            shell_exec('ffmpeg -i "' . $path . $filename . '" -vf "transpose=1,transpose=2" ' . $path . $filename);
            }
             */
            }
        }

        return $to_return;
    }

    public function download(Request $request)
    {
        $broadcast_id = $request->get('broadcast_id');

        $file_name = $request->get('file_name');

        $broadcast = Broadcast::find($broadcast_id);

        if (!is_null($broadcast)) {
            $file_name = $broadcast->filename;
        }

        $path = base_path('wowza_store' . DIRECTORY_SEPARATOR . $file_name);

        if (is_file($path) && file_exists($path)) {
            return response()->download($path, $file_name . '.mp4');
        }
    }

    private function handle_image_file_upload($request, $broadcast_id, $user_id)
    {
        $image = $request->input('image');

        $thumbnail_image = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
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

            $base_64_data = $request->input('image');

            $base_64_data = str_replace('datagea:im/jpeg;base64,', '', $base_64_data);
            $base_64_data = str_replace('data:image/png;base64,', '', $base_64_data);

            File::put($path . $thumbnail_image, base64_decode($base_64_data));

            return $thumbnail_image;
        }

        return $thumbnail_image;
    }

    private function delete_file_from_wowza_store($file_path)
    {
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
}
