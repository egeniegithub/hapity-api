<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\BroadcastViewer;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use URL;

class BroadcastsController extends Controller
{

    private $server;

    public function __construct()
    {
        $this->server = $this->getRandIp();
    }

    public function start_web_cast()
    {
        $data = User::with('profile')->where('id', Auth::id())->first()->toArray();

        $server = $this->server;

        return view('webcast', compact('data', 'server'));
    }

    public function create_content()
    {
        $data = User::with('profile')->where('id', Auth::id())->first()->toArray();
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
                $broadcast_data['filename'] = '';
                $broadcast_data['video_name'] = '';
                $broadcast_data['broadcast_image'] = $broadcast_image;
                $broadcast_data['share_url'] = '';
                // dd($broadcast_data);
                DB::table('broadcasts')->insert($broadcast_data);
                $broadcast_id = DB::getPdo()->lastInsertId();

                $share_url = URL::to('/view-broadcast') . '/' . $broadcast_id;
                $data['share_url'] = $share_url;
                // DB::table('broadcasts')->where('id',$broadcast_id)->update($data);
                Broadcast::where('id', $broadcast_id)->update($data);
                $response = array('status' => 'success', 'broadcast_id' => $broadcast_id, 'share_url' => $share_url);

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

            // $this->config->load('pusher_config');
            // $this->load->library('Pusher');
            // $pusher = new Pusher('ed469f4dd7ae71e71eb8', 'df53e45b5e538cf561e4', '129559');
            // $channel_name = 'Broadcast-' . $broadcast_id;
            // $event_name = 'Broadcast';
            // $pusher_data = array('comment' => '',
            //     'viewer_list' => '',
            //     'user_name' => '',
            //     'broadcast_id' => '',
            //     'user_id' => '',
            //     'status' => 'close',
            //     'profile_picture' => '',
            // );
            // $pusher->trigger($channel_name, $event_name, $pusher_data);
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
            ->get()
            ->toArray();

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
                        // $this->db->update('broadcast', $data, "id = $broadcast_id");
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
            ->get()
            ->toArray();
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

                /*if ($data['type'] != 'joomla') {
                $go = $data['url'] . '?action=hpb_hp_edit_broadcast';
                } else {
                $go = $data['url'] . 'index.php?option=com_hapity&task=savebroadcast.editBroadcastData';
                }*/

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
            $broadcast_data = Broadcast::find($broadcast_id)->toArray();
            return view('edit-content', compact('broadcast_data'));
        } else {
            return back();
        }

    }

    private function getRandIp()
    {
        if (env('APP_ENV') == 'local') {
            return '192.168.20.251';
        } else {
            $ip = array(0 => '52.18.33.132', 1 => '52.17.132.36');
            $index = rand(0, 1);
            return $ip[$index];
        }
    }
}
