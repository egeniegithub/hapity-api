<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
use App\PluginId;
use App\User;
use App\UserProfile;
use Auth;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class CreatecontentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create_content_submission(Request $request)
    {
        $rules = array(
            'title' => 'required',            
            'description' => 'required',            
            'video' => 'required',
            'image' => 'required'
            
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

        return redirect::to('dashboard')->with('flash_message', 'Broadcast Uploaded Successfull');
    }

    public function edit_content_submission(Request $request)
    {

        $rules = array(
            'title' => 'required',
            'description' => 'required',
            'user_id' => 'required',
            'stream_id' => 'required',
            'stream_url' => 'required',
        );
        $messages = array(
            'title.required' => 'Title is required.',
            'description.required' => 'Description is required.',
            'user_id.required' => 'User ID is required.',
            'stream_id.required' => 'Broadcast ID is required.',
            'stream_url.required' => 'Stream URL is required.',
        );
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
            // $path = storage_path('app\public\broadcast');
            $path = base_path('wowza_store');
            $video_path = $video_file->move($path, $filename);
            //Making Stream URL
            $stream_url = "rtmp://";
            $server = $this->getRandIp();
            $stream_url .= $server;
            $stream_url .= ":1935/live/" . $stream_urlx;
            $update_broad['stream_url'] = $stream_url;
            $update_broad['filename'] = $filename;
            $streamURL = Broadcast::where(['id' => $broadcast_id])->first()->toArray();
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
            $path = public_path('images/broadcasts/' . Auth::user()->id . DIRECTORY_SEPARATOR . $broadcast_id);
            // $path = storage_path('app\public');
            $file->move($path, $thumbnail_image);
            $update_broad['broadcast_image'] = $thumbnail_image;
            $streamURL = Broadcast::where(['id' => $broadcast_id])->first()->toArray();
            $filename = $streamURL['broadcast_image'];

            $file_path = public_path('images/broadcasts' . DIRECTORY_SEPARATOR . Auth::user()->id . DIRECTORY_SEPARATOR . $broadcast_id . DIRECTORY_SEPARATOR . $filename);
            // dd($file_path);
            if (is_file($file_path)) {
                unlink($file_path);
            }

        }
        // dd($request->all());
        Broadcast::find($broadcast_id)->update($update_broad);
        // $broadcast->update($update_broad);
        if (isset($input['token']) && !empty($input['token'])) {
            $this->make_plugin_call_edit($broadcast_id, Auth::user()->id);
        }
        return redirect::to('dashboard')->with('flash_message', 'Broadcast Updated Successfull');
    }

    public function view_broadcast($broadcast_id)
    {
        $filename = '';
        $user_id = \Auth::user()->id;
        $broadcast = Broadcast::with('broadcastsComments')->where('id', $broadcast_id)->first()->toArray();

        if (!empty($broadcast)) {
            if ($user_id == '') {

                // $data['APP_ID'] = $this->config->item('APP_ID');
                // $data['APP_KEY'] = $this->config->item('APP_KEY');
                // $data['APP_SECRET'] = $this->config->item('APP_SECRET');
                $filename = $this->get_name_from_link($broadcast['stream_url']);
                return view('view-broadcast', compact('broadcast', 'data'));
            } else {
                // $data['notifications'] = $this->get_notifications($user_id);
                // $data['notification_count'] = $this->get_notification_count($user_id);
                // $app_id = $this->config->item('APP_ID');
                // $app_key = $this->config->item('APP_KEY');
                // $app_secret = $this->config->item('APP_SECRET');
                // $pusher = new Pusher( $app_key, $app_secret, $app_id, array( 'encrypted' => true ) );
                // $data['APP_ID'] = $this->config->item('APP_ID');
                // $data['APP_KEY'] = $this->config->item('APP_KEY');
                // $data['APP_SECRET'] = $this->config->item('APP_SECRET');
                $filename = $this->get_name_from_link($broadcast['stream_url']);
                // $this->load->view('header',$data['broadcast']);
                // $this->load->view('view-broadcast',$data);
                // $this->load->view('footer');
            }
            return view('view-broadcast', compact('broadcast', 'filename'));
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

    private function make_plugin_call_upload($bid, $uid)
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

    private function get_name_from_link($link)
    {
        $name = "";
        $token = strtok($link, '/');
        while ($token !== false) {
            $name = $token;
            $token = strtok('/');
        }

        return $name;
    }

    // public function get_notifications($user_id){
    //     $query = $this->db->query("select no.user_id as user_id, no.status,username, profile_picture, broadcast_image, bc.id as broadcast_id, no.id as noti_id, type from broadcast_notification as no,user as u,broadcast as bc where no.user_id <> $user_id and no.broadcast_id = bc.id and bc.user_id = $user_id and sid = no.user_id order by no.id desc LIMIT 50");
    //     $response = array();
    //     foreach ($query->result_array() as $row) {
    //         if($row['type']=='like')
    //             $row['message'] = 'likes your broadcast.';
    //         else if($row['type']=='comment')
    //             $row['message'] = 'has commented your on broadcast.';

    //         $response[] = $row;
    //     }
    //     return $response;
    // }
    // public function get_notification_count($user_id){
    //     $query = $this->db->query("select count(no.user_id) as count from broadcast_notification as no,broadcast as bc where no.user_id <> $user_id and no.broadcast_id = bc.id and bc.user_id = $user_id and no.status='unread' ");
    //     $row = $query->row();
    //     return $row->count;
    // }

    private function make_plugin_call_edit($bid, $uid)
    {
        $broadcast_id = $bid;
        $broadcast = Broadcast::find($bid);
        $user = User::find($uid);
        $plugins = PluginId::where(['user_id' => $uid]);
        if (isset($plugins) && !empty($plugins)) {
            foreach ($plugins as $data) {
                $title = $broadcast['title'];
                $description = $broadcast['description'];
                $stream_url = str_replace("/live/", "/vod/", $broadcast['stream_url']);
                $image = $broadcast['broadcast_image'];

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
                            'key' => $user['auth_key'],
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_drupal' => $broadcast['post_id_drupal'],
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
                            'post_id_wp' => $broadcast['post_id'],
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
                            'post_id_joomla' => $broadcast['post_id_joomla'],
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

    //params - token, user_id, stream_id, stream_url
    public function deleteBroadcast(Request $request)
    {
        $user_id = $request->user_id;
        $stream_id = $request->stream_id;
        $rules = array(
            'token' => 'required',
            'user_id' => 'required',
            'stream_id' => 'required',
        );
        $messages = array(
            'token.required' => 'Token is required.',
            'user_id.required' => 'User ID is required.',
            'stream_id.required' => 'Stream ID is required.',
        );

        // $validator=Validator::make($request->all(),$rules,$messages);
        // if($validator->fails())
        // {
        //     $messages=$validator->messages();
        //     return response()->json($messages);
        // }
        $streamURL = Broadcast::where(['id' => $stream_id])->first()->toArray();
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

        // return back()->with('message','Record Delete Successfull ');
        $response['status'] = "success";
        $response['response'] = "deletebroadcast";
        $response['message'] = "deleted successfully";

        return response()->json($response);

    }

}
