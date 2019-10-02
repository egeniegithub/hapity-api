<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use URL;
use App\User;
use App\UserProfile;
use App\PluginId;
use Auth;
use App\Broadcast;

class CreatecontentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create_content_submission(Request $request){
    		$video_name = '';
            $title = $request->title;
            $description = $request->description;
            $geo_location = "0,0";
            $allow_user_messages = false;
            $user_id = $request->user_id;
            $is_sensitive = 'no';
            $post_plugin = true;
            $token = $request->token;
            $stream_urlx = md5(microtime().rand()).".stream";
            // $extension = $request->extension;

            $stream_url = "";
            $server = "";
            $filename = "";
            // upload video
            if($request->hasFile('video')){
            	$file = $request->file('video');
            	$extension = $file->getClientOriginalExtension();
	            $video_name = $stream_urlx.'.'.$extension;
	            // $imageName = 'profile_picture_' . $user_id . '.' . $extension;
	            $path = STORAGE_PATH.'/'.$user_id."/"."videos/";
	            $file->move($path, $video_name);
                //Making Stream URL
	         	$stream_url = "rtmp://";
                $server = $this->getRandIp();
                $stream_url .= $server;
                $stream_url .= ":1935/live/" . $stream_urlx;
            }
            // image 
            if($request->hasFile('image')){
            	$file = $request->file('image');
            	$extension = $file->getClientOriginalExtension();
	            $filename = time().'.'.$extension;
	            // $imageName = 'profile_picture_' . $user_id . '.' . $extension;
	            $path = STORAGE_PATH.'/'.$user_id."/"."images/";
	            $file->move($path, $filename);
            }


            if (!$post_plugin)
                $post_plugin = 'false';

            $date = date('Y-m-d h:i:s', time());
            if (auth::user()->id) {
            	$user_data = UserProfile::find($user_id);
                // $user_data = $this->get_user_profile($user_id);
                $is_sensitive = $user_data->is_sensitive;

                if ($allow_user_messages == '')
                    $allow_user_messages = 'yes';

               	$broadcast_data['title'] 				= $title;
               	$broadcast_data['description'] 			= $description;
               	$broadcast_data['geo_location'] 		= $geo_location;
               	$broadcast_data['allow_user_messages'] 	= $allow_user_messages;
               	$broadcast_data['user_id'] 				= $user_id;
               	$broadcast_data['stream_url'] 			= $stream_url;
               	$broadcast_data['created_at'] 			= $date;
               	$broadcast_data['is_sensitive'] 		= $is_sensitive;
               	$broadcast_data['status'] 				= 'offline';
               	$broadcast_data['filename'] 			= $video_name;
               	$broadcast_data['video_name'] 			= $video_name;
               	$broadcast_data['broadcast_image'] 			= $filename;
               	$broadcast_data['share_url']			= '';
     			// dd($broadcast_data);
                DB::table('broadcasts')->insert($broadcast_data);
                // Broadcast::UpdateOrCreate($broadcast_data);
                $broadcast_id = DB::getPdo()->lastInsertId();

		        $share_url = URL::to('/view-broadcast').'/'.$broadcast_id;
		        
		        $data['share_url'] = $share_url;
		        // DB::table('broadcasts')->where('id',$broadcast_id)->update($data);
                Broadcast::where('id',$broadcast_id)->update($data);
		        $response = array('status' => 'success', 'broadcast_id' => $broadcast_id, 'share_url' => $share_url);
		        // return $response;
                $response['stream_url'] = $stream_url;
                $response['status'] = 'success';
                $response['video'] = $_FILES['video']['error'];
                $bid = $response['broadcast_id'];
                $path = '';
                
                $result = $this->make_plugin_call_upload($bid,$user_id);
            }
            return back();
        } 

    public function view_broadcast($broadcast_id){
             $user_id = \Auth::user()->id;
             $bc_id = Broadcast::find($broadcast_id)->id;
                if(isset($bc_id) && !empty($bc_id)){
                    if($user_id == ''){
                        $broadcast = Broadcast::with('broadcastsComments')->first()->toArray();
                        dd($broadcast);
                        // $data['APP_ID'] = $this->config->item('APP_ID');
                        // $data['APP_KEY'] = $this->config->item('APP_KEY');
                        // $data['APP_SECRET'] = $this->config->item('APP_SECRET');
                        // $data['filename'] = $this->get_name_from_link($data['broadcast']['stream_url']);
                        
                        // $this->load->view('header',$data);
                        // //$this->load->view('view-broadcast-logout',$data);
                        // $this->load->view('view-broadcast',$data);
                        // $this->load->view('footer');
                    }
                    else{
                        $broadcast = Broadcast::with('broadcastsComments')->first()->toArray();
                        dd($broadcast);
                        // $query = $this->db->query("select profile_picture,username,id,comment,date from broadcast_comments, user where broadcast_id = $broadcast_id and sid = user_id ORDER BY id ASC");
                        // $comments = array();
                        // foreach ($query->result_array() as $row) {
                        //    $row['comment'] = stripslashes($row['comment']);
                        //    $comments[] = $row;
                        // } 
                        // $data['comments'] = $comments;
                        // $data['broadcast_id'] = $broadcast_id;
                        // $data['broadcast'] = $this->get_broadcast($broadcast_id);
                        // $data['user_id'] = $this->session->userdata('user_id');
                        // $data['notifications'] = $this->get_notifications($user_id);
                        // $data['notification_count'] = $this->get_notification_count($user_id);
                        // $data['notification_view'] = $this->load->view('notifications', $data, TRUE);
                        // $data['hapity_header_view'] = $this->load->view('hapity_header', NULL, TRUE);
                        // $data['hapity_footer_view'] = $this->load->view('hapity_footer', NULL, TRUE);
                        // $data['userdata'] = $this->get_user_profile($user_id);
                        // $app_id = $this->config->item('APP_ID');
                        // $app_key = $this->config->item('APP_KEY');
                        // $app_secret = $this->config->item('APP_SECRET');
                        // $pusher = new Pusher( $app_key, $app_secret, $app_id, array( 'encrypted' => true ) );
                        // $data['APP_ID'] = $this->config->item('APP_ID');
                        // $data['APP_KEY'] = $this->config->item('APP_KEY');
                        // $data['APP_SECRET'] = $this->config->item('APP_SECRET');
                        // $data['user_id'] = $user_id;
                        // $data['filename'] = $this->get_name_from_link($data['broadcast']['stream_url']);
                        // $this->load->view('header',$data['broadcast']);
                        // $this->load->view('view-broadcast',$data);
                        // $this->load->view('footer');
                    }
                }
                else{                   
                    return back();
                }
             
        }
    public function update_img_broadcast($broadcast_id, $path){
        $data = array(
                'broadcast_image'=>$path,  
            );
        $this->db->update('broadcast', $data, "id = $broadcast_id ");
        return $path;
    }

    private function getRandIp()
    {
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

   private function get_name_from_link($link){
        $name = "";
        $token = strtok($link, '/');
        while($token !== false){
            $name = $token;
            $token = strtok('/');
        }

        return $name;
    }
}
