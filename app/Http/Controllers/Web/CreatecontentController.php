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
                // $path = STORAGE_PATH.'/'.$user_id."/"."videos/";
                $path = base_path('wowza_store');
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
                 Broadcast::create($broadcast_data);
                // DB::table('broadcasts')->insert($broadcast_data);
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

        // public function edit_content_submission(){
        //     $user_id = $this->session->userdata('user_id');
        //     $title = $this->input->post('title');
        //     $description = $this->input->post('description');
        //     $stream_id = $this->input->post('bid');
        //     $stream_urlx = $this->input->post('stream_url');
        //     $token = $this->input->post("token");
        //     if($this->is_broadcast($stream_id) && $user_id!=" " && $user_id!= NULL){

        //         $qry = "Select * from broadcast where  id='$stream_id' ";
        //         $response = $this->db->query($qry);
        //         $response = $response->result_array();
        //         $stream_urllll = $response[0]['stream_url'];
        //         $user_id = $response[0]['user_id'];
        //         $broadcast_imageg = $response[0]['broadcast_image'];

        //         if (($_FILES['image']['error'] === UPLOAD_ERR_OK)) {
        //             define('UPLOAD_DIR', '/var/www/vhosts/api/api/uploads/broadcast_images/');
        //             define('UPLOAD_URL', 'https://api.hapity.com/uploads/broadcast_images/');

        //             $name = time();
        //             $pathtosave = UPLOAD_DIR . $name . ".jpg";
        //             $urltosave = UPLOAD_URL . $name . ".jpg";

        //             if (!empty($broadcast_imageg)) {
        //                 $pathFragments = explode('/', $broadcast_imageg);
        //                 $broadcast_imageg = end($pathFragments);
        //             }
        //             foreach (glob(UPLOAD_DIR . "/*") as $filename) {
        //                 $pos = strpos($filename, $broadcast_imageg);
        //                 if ($pos === false) {

        //                     // string needle NOT found in haystack
        //                 } else {
        //                     $command = "rm " . $filename;
        //                     $error = shell_exec("$command");
        //                 }
        //             }
        //             $imageFile = $_FILES['image']['name'];
        //             $imageFile_tomove = $_FILES['image']['tmp_name'];
        //             move_uploaded_file($imageFile_tomove, $pathtosave);
        //             $path = $this->Broadcast->update_img_broadcast($stream_id, $urltosave);

        //         }

        //         if (($_FILES['video']['error'] === UPLOAD_ERR_OK)) {
        //             if ($stream_urlx == '') {

        //                 if (!empty($stream_urllll)) {
        //                     $pathFragments = explode('/', $stream_urllll);
        //                     $stream_urlx = end($pathFragments);
        //                 }
        //             }
        //             if ($stream_urlx != '') {
        //                 $path = "/home/san/live/";
        //                 $files = array();
        //                 foreach (glob($path . "/*") as $filename) {
        //                     $pos = strpos($filename, $stream_urlx);
        //                     if ($pos === false) {

        //                         // string needle NOT found in haystack
        //                     } else {
        //                         $command = "rm " . $filename;
        //                         $error = shell_exec("$command");
        //                     }
        //                 }

        //                 $videofile = $_FILES['video']['name'];
        //                 $videofile_tomove = $_FILES['video']['tmp_name'];
        //                 if ($extension == '') {
        //                     $info = pathinfo($videofile);
        //                     $ext = $info['extension']; // get the extension of the file
        //                     $newname = $stream_urlx . "." . $ext;

        //                 } else {
        //                     $newname = $stream_urlx . "." . $extension;
        //                 }

        //                 //$temp_pathtosave = "/home/san/live/temp-" . $newname;
        //                 $pathtosave = "/home/san/live/" . $newname;

        //                 move_uploaded_file($videofile_tomove, $pathtosave);

        //                 /*$shell_exec = shell_exec("ffprobe -loglevel error -select_streams v:0 -show_entries stream_tags=rotate -of default=nw=1:nk=1 $temp_pathtosave");

        //                 if($shell_exec == 90){
        //                     $shell_exec = shell_exec('ffmpeg -i "'.$temp_pathtosave.'" -vf "transpose=1,transpose=2" '. $pathtosave);
        //                     shell_exec('rm '.$temp_pathtosave);
        //                 }*/
        //                 $stream_url = "rtmp://";
        //                 $server = $this->getRandIp();
        //                 $stream_url .= $server;
        //                 $stream_url .= ":1935/live/" . $this->input->post('stream_url');

        //             }
        //         }

        //         $broadcast_data = $this->get_broadcast_by_id($stream_id);
        //         $data = array();
        //         if($broadcast_data['title'] != $title){
        //             $data['title'] = $title;
        //         }

        //         if($broadcast_data['description'] != $description){
        //             $data['description'] = $description;
        //         }
                
        //         $this->updatbroadcast($stream_id, $data);

        //         $this->make_plugin_call_edit($stream_id);
        //     }

        //     redirect('main/', 'refresh');
        // } 

    public function view_broadcast($broadcast_id){
            $filename = '';
             $user_id = \Auth::user()->id;
             $broadcast = Broadcast::with('broadcastsComments')->where('id',$broadcast_id)->first()->toArray();

                if(!empty($broadcast)){
                    if($user_id == ''){

                        // $data['APP_ID'] = $this->config->item('APP_ID');
                        // $data['APP_KEY'] = $this->config->item('APP_KEY');
                        // $data['APP_SECRET'] = $this->config->item('APP_SECRET');
                        $filename = $this->get_name_from_link($broadcast['stream_url']);
                        return view('view-broadcast',compact('broadcast','data'));
                    }
                    else{
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
                    return view('view-broadcast',compact('broadcast','filename'));
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
        if(env('APP_ENV') == 'local'){
            return '192.168.20.251';
        }else{
            $ip = array(0 => '52.18.33.132', 1 => '52.17.132.36');
            $index = rand(0, 1);
            return $ip[$index];
        }
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
}
