<?php

namespace App\Http\Controllers;

use App\PluginId;
use Illuminate\Http\Request;
use Validator;
use App\User;
use App\UserProfile;
use App\UserSocial;
use App\Broadcast;

class BroadcastController extends Controller
{
    //
    public function __construct()
    {
        auth()->setDefaultDriver('api');
//        $this->middleware('auth:api', ['except' => ['uploadBroadcast', 'editBroadcast', 'deleteBroadcast']]);
    }

    function uploadBroadcast (Request $request) {

        $rules = array(
            'title' => 'required',
            'geo_location' => 'required',
            'description' => 'required',
            'user_id' => 'required',
            'video' => 'required',
            'is_sensitive' => 'required',
            'post_plugin' => 'required',
            'stream_url' => 'required'
        );
        $messages = array(
            'title.required' => 'Title is required.',
            'geo_location.required' => 'Geo location is required.',
            'description.required' => 'Description is required.',
            'user_id.required' => 'User ID is required.',
            'video.required' => 'Broadcast video is required.',
            'is_sensitive.required' => 'Sensitivity flag is required.',
            'post_plugin.required' => 'Plugin flag is required.',
            'stream_url.required' => 'Stream URL is required.',
        );

        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0]
            );
            return response()->json($response);
        }

        $input = $request->all();
        $stream_urlx = $input['stream_url'];
        $stream_url = "";
        $server = "";
        $filename = "";
        $thumbnail_image = "default001.jpg";
        if($request->hasFile('video')){

            $video_file = $request->file('video');
            $info = pathinfo($video_file->getClientOriginalName());
            $ext = $info['extension'];
            $filename = $stream_urlx . "." . $ext;
            $path = storage_path('app\public\broadcast');
            $video_path = $video_file->move($path, $filename);
            //Making Stream URL
            $stream_url = "rtmp://";
            $server = $this->getRandIp();
            $stream_url .= $server;
            $stream_url .= ":1935/live/" . $stream_urlx;

//            $temp_pathtosave = "/home/san/live/temp-" . $filename;
//            $pathtosave = "/home/san/live/" . $filename;

//            $shell_exec = shell_exec("ffprobe -loglevel error -select_streams v:0 -show_entries stream_tags=rotate -of default=nw=1:nk=1 $path.$filename");
//
//            if ($shell_exec == 90) {
//                shell_exec('rm ' . $path.$filename);
//                shell_exec('ffmpeg -i "' . $path.$filename . '" -vf "transpose=1,transpose=2" ' . $path.$filename);
//            }
        }
        if($request->hasFile('broadcast_image')){

            $file = $request->file('broadcast_image');
            $info = pathinfo($file->getClientOriginalName());
            $ext = $info['extension'];
            $thumbnail_image = Str::random(6).'_'.now()->timestamp.'.'. $ext;
            $path = storage_path('app\public');
            $file->move($path, $thumbnail_image);
        }
        $user = User::find($input['user_id']);
        $broadcast = new Broadcast();
        $broadcast->title = $input['title'];
        $broadcast->geo_location = $input['geo_location'];
        $broadcast->description = $input['description'];
        $broadcast->is_sensitive = $input['is_sensitive'];
        $broadcast->stream_url = $stream_url;
        $broadcast->broadcast_image = $thumbnail_image;
        $broadcast->filename = $filename;
        $broadcast->status = 'offline';
        $broadcast->video_name = $video_file->getClientOriginalName();
        $user->broadcasts()->save($broadcast);
        $share_url = route('broadcasts/view/'.$broadcast->id);
        $inserted_broadcast = Broadcast::find($broadcast->id);
        $inserted_broadcast->share_url = $share_url;
        $inserted_broadcast->save();
        $response = array('status' => 'success', 'broadcast_id' => $broadcast->id, 'share_url' => $share_url);
        $response['stream_url'] = $stream_url;
        $response['server'] = $server;
        $response['response'] = "uploadbroadcast";
        $response['video'] = $_FILES['video']['error'];
        if ($input['post_plugin']) {
            $this->make_plugin_call_upload($broadcast->id, $input['user_id']);
        }
        return response()->json(['response' => $response]);

    }

    function editBroadcast (Request $request) {

        $rules = array(
            'title' => 'required',
            'description' => 'required',
            'user_id' => 'required',
            'stream_id' => 'required',
            'stream_url' => 'required'
        );
        $messages = array(
            'title.required' => 'Title is required.',
            'description.required' => 'Description is required.',
            'user_id.required' => 'User ID is required.',
            'stream_id.required' => 'Broadcast ID is required.',
            'stream_url.required' => 'Stream URL is required.',
        );

        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0]
            );
            return response()->json($response);
        }

        $input = $request->all();
        $stream_urlx = $input['stream_url'];
        $update_broad = array();
        $broadcast = Broadcast::find($input['stream_id']);
        if(isset($input['title']) && !empty($input['title'])){

            $update_broad['title'] = $input['title'];
        }
        if(isset($input['description']) && !empty($input['description'])){

            $update_broad['description'] = $input['description'];
        }
        if($request->hasFile('video')){

            $video_file = $request->file('video');
            $info = pathinfo($video_file->getClientOriginalName());
            $ext = $info['extension'];
            $filename = $stream_urlx . "." . $ext;
            $path = storage_path('app\public\broadcast');
            $video_path = $video_file->move($path, $filename);
            //Making Stream URL
            $stream_url = "rtmp://";
            $server = $this->getRandIp();
            $stream_url .= $server;
            $stream_url .= ":1935/live/" . $stream_urlx;
            $update_broad['stream_url'] = $stream_url;


            $streamURL = Broadcast::where(['id' => $input['stream_id']])->first()->toArray();
            $filename = $streamURL['filename'];
            Storage::delete($filename);
        }
        if($request->hasFile('broadcast_image')){

            $file = $request->file('broadcast_image');
            $info = pathinfo($file->getClientOriginalName());
            $ext = $info['extension'];
            $thumbnail_image = Str::random(6).'_'.now()->timestamp.'.'. $ext;
            $path = storage_path('app\public');
            $file->move($path, $thumbnail_image);
            $update_broad['broadcast_image'] = $thumbnail_image;

            $streamURL = Broadcast::where(['id' => $input['stream_id']])->first()->toArray();
            $filename = $streamURL['broadcast_image'];
            Storage::delete($filename);
        }
        $broadcast->save($update_broad);

        $response['status'] = 'success';
        $response['response'] = 'editbroadcast';

        if (isset($input['plugin_auth_key']) && !empty($input['plugin_auth_key'])) {
            $this->make_plugin_call_edit($input['stream_id'], $input['user_id']);
        }
        return response()->json($response);
    }

    //params - token, user_id, stream_id, stream_url
    function deleteBroadcast (Request $request) {
        $input = $request->all();

        $rules = array(
            'token' => 'required',
            'user_id' => 'required',
            'stream_id' => 'required'
        );
        $messages = array(
            'token.required' => 'Token is required.',
            'user_id.required' => 'User ID is required.',
            'stream_id.required' => 'Stream ID is required.',
        );

        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json($messages);
        }
        $streamURL = Broadcast::where(['id' => $input['stream_id']])->first()->toArray();
        $filename = $streamURL['filename'];
        Broadcast::where('user_id', $input['user_id'])->where(['id' => $input['stream_id']])->delete();

        Storage::delete($filename);
        $response['status'] = "success";
        $response['response'] = "deletebroadcast";
        $response['message'] = "deleted successfully";

        return response()->json($response);

    }

    function getAllBroadcastsforUser (Request $request) {
        $input = $request->all();

        $rules = array(
//            'token' => 'required',
            'user_id' => 'required',
        );
        $messages = array(
//            'token.required' => 'Token is required.',
            'user_id.required' => 'User ID is required.',
        );

        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json($messages);
        }
        
        $allUserBroadcast = Broadcast::with('user')->where('user_id', $input['user_id'])->get();

        $response['status'] = "success";
        $response['broadcast'] = [];

        foreach ($allUserBroadcast as $key => $broadcast) {
            $profilePicToGet = $broadcast['user']->profile()->first();

            $broadcastObj['id'] = $broadcast['id'];
            $broadcastObj['geo_location'] = $broadcast['geo_location'];
            $broadcastObj['filename'] = $broadcast['filename'];
            $broadcastObj['title'] = $broadcast['title'];
            $broadcastObj['description'] = $broadcast['description'];
            $broadcastObj['is_sensitive'] = $broadcast['is_sensitive'];
            $broadcastObj['stream_url'] = $broadcast['stream_url'];
            $broadcastObj['status'] = $broadcast['status'];
            $broadcastObj['broadcast_image'] = $broadcast['broadcast_image'];
            $broadcastObj['share_url'] = $broadcast['share_url'];
            $broadcastObj['username'] = $broadcast['user']->username;
            $broadcastObj['user_id'] = $broadcast['user']->id;
            $broadcastObj['profile_picture'] = $profilePicToGet['profile_picture'];
            array_push($response['broadcast'], (object)$broadcastObj);
        }

        // remove block user need to be implemented here

        return response()->json($response);

    }

    function startBroadcast(Request $request){

        $rules = array(
            'title' => 'required',
            'geo_location' => 'required',
            'user_id' => 'required',
            'is_sensitive' => 'required',
            'post_plugin' => 'required',
            'stream_url' => 'required',
            'thumb_nail' => 'required'
        );
        $messages = array(
            'title.required' => 'Title is required.',
            'geo_location.required' => 'Geo location is required.',
            'user_id.required' => 'User ID is required.',
            'is_sensitive.required' => 'Sensitivity flag is required.',
            'post_plugin.required' => 'Plugin flag is required.',
            'stream_url.required' => 'Stream URL is required.',
            'thumb_nail.required' => 'Thumb nail is required.',
        );
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0]
            );
            return response()->json($response);
        }
        $input = $request->all();
        $stream_url = "rtmp://";
        $server = $this->getRandIp();
        $stream_url .= $server;
        $stream_url .= ":1935/live/" . $this->get('stream_url');
        $thumbnail_image = "default001.jpg";
        if($request->hasFile('thumb_nail')){

            $file = $request->file('thumb_nail');
            $info = pathinfo($file->getClientOriginalName());
            $ext = $info['extension'];
            $thumbnail_image = Str::random(6).'_'.now()->timestamp.'.'. $ext;
            $path = storage_path('app\public');
            $file->move($path, $thumbnail_image);
        }

        $user = User::find($input['user_id']);
        $broadcast = new Broadcast();
        $broadcast->title = $input['title'];
        $broadcast->geo_location = $input['geo_location'];
        $broadcast->description = $input['description'];
        $broadcast->is_sensitive = $input['is_sensitive'];
        $broadcast->stream_url = $stream_url;
        $broadcast->broadcast_image = $thumbnail_image;
        $broadcast->status = 'online';
        $user->broadcasts()->save($broadcast);
        $share_url = route('broadcasts/view/'.$broadcast->id);
        $inserted_broadcast = Broadcast::find($broadcast->id);
        $inserted_broadcast->share_url = $share_url;
        $inserted_broadcast->save();
        $response = array('status' => 'start', 'broadcast_id' => $broadcast->id, 'share_url' => $share_url);
        $response['stream_url'] = $stream_url;
        $response['server'] = $server;
        if ($input['post_plugin']) {
            $this->make_plugin_call_upload($broadcast->id, $input['user_id']);
        }
        return response()->json(['response' => $response]);

    }

    function updateTimestampBroadcast(Request $request) {
        $rules = array(
            'broadcast_id' => 'required'
        );
        $messages = array(
            'broadcast_id.required' => 'Broadcast video is required.'
        );

        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages = $validator->errors()->all();
            $response = array(
                'status' => 'failure',
                'message' => $messages[0]
            );
            return response()->json($response);
        }

        $input = $request->all();
        $broadcast_id = $input['broadcast_id'];
        $date = date('Y-m-d h:i:s', time());

        $broadcast = Broadcast::find($broadcast_id);
        $broadcast->timestamp = $date;
        $response = array();
        $response['status'] = 'timestamp';
        $this->response($response, 200);
    }

    private function getRandIp() {
        $ip = array(0 => '52.18.33.132', 1 => '52.17.132.36');
        $index = rand(0, 1);
        return $ip[$index];
    }

    private function make_plugin_call_upload($bid, $uid) {

        $share_url = "";
        $broadcast_id = $bid;
        $broadcast = Broadcast::find($bid);
        $user = User::find($uid);
        $plugins = PluginId::where(['user_id' => $uid]);
        if (sizeof($plugins) > 0) {
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
                            'action' => 'hpb_hp_new_broadcast'
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
                            'description' => $description
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
                            'description' => $description
                        )
                    );
                }
                $opts = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                    )
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

    private function make_plugin_call_edit($bid, $uid) {
        $broadcast_id = $bid;
        $broadcast = Broadcast::find($bid);
        $user = User::find($uid);
        $plugins = PluginId::where(['user_id' => $uid]);
        if (sizeof($plugins) > 0) {
            foreach ($plugins as $data) {
                $title = $broadcast['title'];
                $description = $broadcast['description'];
                $stream_url = str_replace("/live/", "/vod/", $broadcast['stream_url']);
                $image = $broadcast['broadcast_image'];


                $headers = array(
                    'Content-type: application/xwww-form-urlencoded'
                );

                if ($data['type'] == 'drupal') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $user['auth_key'],
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_drupal' => $broadcast['post_id_drupal']
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
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_wp' => $broadcast['post_id']
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
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_joomla' => $broadcast['post_id_joomla']
                        )
                    );
                }


                $opts = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                    )
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
}
