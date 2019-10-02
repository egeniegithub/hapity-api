<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BroadcastsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function startwebbroadcast(Request $request) {
        dd($request->all());
            $title = $request->title;
            $description = "";
            $geo_location = $request->geo_location;
            $allow_user_messages = $request->allow_user_messages;
            $user_id = $request->user_id;
            $is_sensitive = $request->is_sensitive;
            $input_server = $request->server;
            
            $post_plugin = $request->post_plugin;
            $broadcast_image = $request->broadcast_image;
            if (!$post_plugin)
                $post_plugin = 'false';
            $stream_url = "rtmp://";
            if ($input_server != '') {
                $server = $input_server;
                $stream_url .= $server;
                $stream_url .= ":1935/live/" . $request->stream_url . '_360p';
            } else {
                $server = $this->getRandIp();
                $stream_url .= $server;
                $stream_url .= ":1935/live/" . $request->stream_url;
            }

            $token = $request->token;
            if (!$this->validate_token($token)) {
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
                if ($this->User->is_user($user_id)) {
                    if ($allow_user_messages == ''){
                        $allow_user_messages = 'yes';
                    }

                    $response = $this->Broadcast->startBroadcast($title, $description, $geo_location, $allow_user_messages, $user_id, $stream_url, $date, $is_sensitive, "online", '');
                    $response['stream_url'] = $stream_url;
                    //$response['status'] = 'start';
                    $bid = $response['broadcast_id'];
                    $path = 'https://www.hapity.com/images/default001.jpg';
                    if($broadcast_image){
                        $path = $this->saveImage($broadcast_image, 'broadcast');
                        $path = $this->Broadcast->update_img_broadcast($bid, $path);
                    }
                    

                    if ($post_plugin == 'true') {
                        $this->make_plugin_call($bid, $path);
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

    public function update_timestamp_broadcast(Request $request){
       $data['updated_at'] = $date;
       $broadcast_id = $request->broadcast_id;
       \DB::table('broadcasts')->where('id',$broadcast_id)->update($data);
   }

    // public function offline_broadcast() {

    //         $broadcast_id = $this->input->get('broadcast_id');

    //         $token = $this->input->get("token");
    //         if (!$this->validate_token($token)) {
    //             $response = array('status' => 'failure', 'error' => 'Invalid Token');
    //             $result = json_encode($response, true);
    //             echo $result;
    //             return;
    //         }

    //         if ($broadcast_id) {
    //             $this->Broadcast->set_broadcast_offline($broadcast_id);
    //             $response = array();
    //             $response['status'] = 'offline';
    //             $this->User->delete_viewers($broadcast_id);
    //             $this->config->load('pusher_config');
    //             $this->load->library('Pusher');
    //             $pusher = new Pusher('ed469f4dd7ae71e71eb8', 'df53e45b5e538cf561e4', '129559');
    //             $channel_name = 'Broadcast-' . $broadcast_id;
    //             $event_name = 'Broadcast';
    //             $pusher_data = array('comment' => '',
    //                 'viewer_list' => '',
    //                 'user_name' => '',
    //                 'broadcast_id' => '',
    //                 'user_id' => '',
    //                 'status' => 'close',
    //                 'profile_picture' => '',
    //             );
    //             $pusher->trigger($channel_name, $event_name, $pusher_data);
    //             $this->make_plugin_call_edit($broadcast_id);
    //             $result = json_encode($response, true);
    //             echo $result;
    //             return;
    //         } else {
    //             $response = array('status' => 'failure', 'error' => 'missing parameter');
    //             $result = json_encode($response, true);
    //             echo $result;
    //             return;
    //         }
    //     }
}
