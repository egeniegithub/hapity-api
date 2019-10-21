<?php
namespace App\Http\Helpers;

use App\Broadcast;

class PluginFunctions
{
    public function make_plugin_call_upload($bid){   
        $share_url = '';
        $broadcast_id = $bid;
        $broadcast = Broadcast::leftJoin('users as u','u.id','=','broadcasts.user_id')
                        ->leftJoin('user_profiles as up','up.user_id','=','u.id')
                        ->rightJoin('plugin_ids as pid','pid.user_id','=','u.id')
                        ->where('broadcasts.id',$broadcast_id)->get();

        if (sizeof($broadcast) > 0) {
            foreach ($broadcast as $data) {
               $title = $data->title;
                $description = $data->description;
                $stream_url = str_replace("/live/", "/vod/", $data->stream_url);
        
                if($data->broadcast_image){
                    $image = $data->broadcast_image;
                } else {
                    $image = 'https://staging.hapity.com/images/default001.jpg';
                }
                

                if ($data->type == 'drupal') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $data->auth_key,
                            'broadcast_image' => $data->broadcast_image,
                            'description' => $description,
                            'action' => 'hpb_hp_new_broadcast',
                        )
                    );
                } else if ($data->type == 'wordpress') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $data->auth_key,
                            'broadcast_image' => $data->broadcast_image,
                            'description' => $description,
                        )
                    );
                } else if ($data->type == 'joomla') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $data->auth_key,
                            'broadcast_image' => $data->broadcast_image,
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

                if ($data->type == 'wordpress') {
                    $go = $data->url . '?action=hpb_hp_new_broadcast';
                } else if ($data->type == 'drupal') {
                    $go = $data->url;
                } else if ($data->type == 'joomla') {
                    $go = $data->url . 'index.php?option=com_hapity&task=savebroadcast.getBroadcastData';
                }
                $result = file_get_contents($go, false, $context);
                $result = json_decode($result, true);
            
                if (!empty($result)) {
                    $update_broadcast = Broadcast::find($bid);
                    $flag = 0;
                    $update_broadcast->share_url = $result['post_url'];

                    $share_url = $result['post_url'];

                    $wp_post_id = isset($result['post_id_wp']) ? $result['post_id_wp'] : '';
                    $post_id_joomla = isset($result['post_id_joomla']) ? $result['post_id_joomla'] : '';
                    $drupal_post_id = isset($result['drupal_post_id']) ? $result['drupal_post_id'] : '';

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

    public function make_plugin_call($broadcast_id, $image){
        $broadcast = array();
        $broadcast = Broadcast::leftJoin('users as u','u.id','=','broadcasts.user_id')
                    ->leftJoin('user_profiles as up','up.user_id','=','u.id')
                    ->rightJoin('plugin_ids as pid','pid.user_id','=','u.id')
                    ->where('broadcasts.id',$broadcast_id)->get();

        if (count($broadcast) > 0) {
            foreach ($broadcast as $data) {
                $title = $data->title;
                $description = $data->description;
                $stream_url = $data->stream_url;
                $status = $data->status;

                $headers = array(
                    'Content-type: application/xwww-form-urlencoded',
                );
                if ($data->type == 'drupal') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $data->auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                            'action' => 'hpb_hp_new_broadcast',
                        )
                    );
                } else if ($data->type == 'wordpress') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $data->auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                        )
                    );
                } else if ($data->type == 'joomla') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $data->auth_key,
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
                if ($data->type == 'wordpress') {
                    $go = $data->url . '?action=hpb_hp_new_broadcast';
                } else if ($data->type == 'drupal') {
                    $go = $data->url; // . '?action=hpb_hp_new_broadcast';
                } else if ($data->type == 'joomla') {
                    $go = $data->url . 'index.php?option=com_hapity&task=savebroadcast.getBroadcastData';
                }

                $result = file_get_contents($go, false, $context);
                $result = json_decode($result, true);

                if (!empty($result)) {
                    $update_broadcast = Broadcast::find($broadcast_id);
                    $flag = 0;
                    $update_broadcast->share_url = $result['post_url'];

                    $share_url = $result['post_url'];

                    $wp_post_id = isset($result['post_id_wp']) ? $result['post_id_wp'] : '';
                    $post_id_joomla = isset($result['post_id_joomla']) ? $result['post_id_joomla'] : '';
                    $drupal_post_id = isset($result['drupal_post_id']) ? $result['drupal_post_id'] : '';

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
    }

    public function make_plugin_call_edit($broadcast_id){
        $broadcast = array();
        $broadcast = Broadcast::leftJoin('users as u','u.id','=','broadcasts.user_id')
                    ->leftJoin('user_profiles as up','up.user_id','=','u.id')
                    ->rightJoin('plugin_ids as pid','pid.user_id','=','u.id')
                    ->where('broadcasts.id',$broadcast_id)->get();

        if (count($broadcast) > 0) {
            foreach ($broadcast as $data) {
                $title = $data['title'];
                $description = $data->description;
                $stream_url = str_replace("/live/", "/vod/", $data->stream_url);
                $image = $data->broadcast_image;

                $headers = array(
                    'Content-type: application/xwww-form-urlencoded',
                );

                if ($data->type == 'drupal') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $data->auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_drupal' => $data->post_id_drupal,
                        )
                    );
                } else if ($data->type == 'wordpress') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $data->auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_wp' => $data->post_id,
                        )
                    );
                } else if ($data->type == 'joomla') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => 'offline',
                            'key' => $data->auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_joomla' => $data->post_id_joomla,
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

                if ($data->type == 'wordpress') {
                    $go = $data->url . '?action=hpb_hp_edit_broadcast';
                } else if ($data->type == 'drupal') {
                    $go = $data->url . '?action=hpb_hp_edit_broadcast';
                } else if ($data->type == 'joomla') {
                    $go = $data->url . 'index.php?option=com_hapity&task=savebroadcast.editBroadcastData';
                }

                $result = file_get_contents($go, false, stream_context_create($opts));
                $result = json_decode($result, true);
                return $result;
                dd($result);
            }
        }
    }
}