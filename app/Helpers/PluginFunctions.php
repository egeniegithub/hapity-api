<?php
namespace App\Http\Helpers;

use App\Broadcast;
use App\PluginId;
use App\UserProfile;
use Exception;
use Illuminate\Support\Facades\Log;

class PluginFunctions
{
    public function make_plugin_call_upload($broadcast_id)
    {
        $share_url = '';
        $broadcast = Broadcast::with(['user'])->where('id', $broadcast_id)->first();

        if (!is_null($broadcast) && $broadcast->id > 0) {
            $plugin = PluginId::where('user_id', $broadcast->user_id)->orderBy('id', 'DESC')->first();
            $auth_key = UserProfile::where('user_id', $broadcast->user_id)->first()->auth_key;

            if (!is_null($plugin) && $plugin->id > 0 && !empty($auth_key)) {

                $title = $broadcast->title;
                $description = $broadcast->description;
                $status = isset($broadcast->status) ? $broadcast->status : 'offline';
                $stream_url = $broadcast->filename;

                if ($broadcast->broadcast_image) {
                    $image = $broadcast->broadcast_image;
                } else {
                    $image = public_path('images/default001.jpg');
                }

                if ($plugin->type == 'drupal') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'user_id' => $plugin->user_id,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $auth_key,
                            'broadcast_image' => $broadcast->broadcast_image,
                            'description' => $description,
                            'action' => 'hpb_hp_new_broadcast',
                        )
                    );
                } else if ($plugin->type == 'wordpress') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'user_id' => $plugin->user_id,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $auth_key,
                            'broadcast_image' => $broadcast->broadcast_image,
                            'description' => $description,
                        )
                    );
                } else if ($plugin->type == 'joomla') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'user_id' => $plugin->user_id,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $auth_key,
                            'broadcast_image' => $broadcast->broadcast_image,
                            'description' => $description,
                        )
                    );
                }
                $opts = array('http' => array(
                    'method' => 'POST',
                    'header' => array(
                        'Content-type: application/x-www-form-urlencoded',
                        "'user-agent: ". $_SERVER['HTTP_USER_AGENT']."'"),
                    'content' => $postdata,
                ),
                );

                $context = stream_context_create($opts);

                if ($plugin->type == 'wordpress') {
                    $go = $plugin->url . '?action=hpb_hp_new_broadcast';
                } else if ($plugin->type == 'drupal') {
                    $go = $plugin->url;
                } else if ($plugin->type == 'joomla') {
                    $go = $plugin->url . 'index.php?option=com_hapity&task=savebroadcast.getBroadcastData';
                }
                $this->stream_context_default();
                if (strpos($plugin->url, 'localhost') === false) {

                    $domain_available = $this->check_if_domain_is_available($plugin->url);
                    if ($domain_available == true) {
                        $result_str = file_get_contents($go, false, $context);
                        print_r($result_str);exit;


                        $result = json_decode($result_str, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $matches = array();
                            $t = preg_match('/\{(.*?)\}/s', $result_str, $matches);
                            if(isset($matches[0])){
                                $result_str = $matches[0];
                                $result = json_decode($result_str, true);
                            }
                        }
                        Log::info('Broadcast: ' . $broadcast_id . ' Result: ' . json_encode($result));

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
        }

        return $share_url;
    }

    public function make_plugin_call($broadcast_id, $image)
    {
        $broadcast = Broadcast::with(['user'])->where('id', $broadcast_id)->first();

        if (!is_null($broadcast) && $broadcast->id > 0) {
            $plugin = PluginId::where('user_id', $broadcast->user_id)->orderBy('id', 'DESC')->first();
            $auth_key = UserProfile::where('user_id', $broadcast->user_id)->first()->auth_key;

            if (!is_null($plugin) && $plugin->id > 0 && !empty($auth_key)) {

                $title = $broadcast->title;
                $description = $broadcast->description;
                $stream_url = $broadcast->filename;
                $status = isset($broadcast->status) ? $broadcast->status : 'offline';

                $headers = array(
                    'Content-type: application/xwww-form-urlencoded',
                );
                if ($plugin->type == 'drupal') {
                    $postplugin = http_build_query(
                        array(
                            'title' => $title,
                            'user_id' => $plugin->user_id,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                            'action' => 'hpb_hp_new_broadcast',
                        )
                    );
                } else if ($plugin->type == 'wordpress') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'user_id' => $plugin->user_id,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                        )
                    );
                } else if ($plugin->type == 'joomla') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'user_id' => $plugin->user_id,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                        )
                    );
                }
                $opts = array('http' => array(
                    'method' => 'POST',
                    'header' => array(
                        'Content-type: application/x-www-form-urlencoded',
                        "'user-agent: ". $_SERVER['HTTP_USER_AGENT']."'"
                    ),
                    'content' => $postdata,
                ),
                );
                $context = stream_context_create($opts);
                if ($plugin->type == 'wordpress') {
                    $go = $plugin->url . '?action=hpb_hp_new_broadcast';
                } else if ($plugin->type == 'drupal') {
                    $go = $plugin->url; // . '?action=hpb_hp_new_broadcast';
                } else if ($plugin->type == 'joomla') {
                    $go = $plugin->url . 'index.php?option=com_hapity&task=savebroadcast.getBroadcastData';
                }
                $this->stream_context_default();
                if (strpos($plugin->url, 'localhost') === false) {

                    $domain_available = $this->check_if_domain_is_available($plugin->url);
                    if ($domain_available == true) {
                        $result_str = @file_get_contents($go, false, $context);
                        $result = json_decode($result_str, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $matches = array();
                            $t = preg_match('/\{(.*?)\}/s', $result_str, $matches);
                            if(isset($matches[0])){
                                $result_str = $matches[0];
                                $result = json_decode($result_str, true);
                            }
                        }
                        Log::info('Broadcast: ' . $broadcast_id . ' Result: ' . json_encode($result));

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
        }
    }

    public function make_plugin_call_edit($broadcast_id)
    {
        $broadcast = Broadcast::with(['user'])->where('id', $broadcast_id)->first();

        if (!is_null($broadcast) && $broadcast->id > 0) {
            $plugin = PluginId::where('user_id', $broadcast->user_id)->orderBy('id', 'DESC')->first();

            $auth_key = UserProfile::where('user_id', $broadcast->user_id)->first()->auth_key;

            if (!is_null($plugin) && $plugin->id > 0 && !empty($auth_key)) {
                $title = $broadcast->title;
                $description = $broadcast->description;
                $stream_url = $broadcast->filename;
                $image = $broadcast->broadcast_image;

                $status = isset($broadcast->status) ? $broadcast->status : 'offline';

                $headers = array(
                    'Content-type: application/xwww-form-urlencoded',
                );

                if ($plugin->type == 'drupal') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'user_id' => $plugin->user_id,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_drupal' => $broadcast->post_id_drupal,
                        )
                    );
                } else if ($plugin->type == 'wordpress') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'user_id' => $plugin->user_id,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_wp' => $broadcast->post_id,
                        )
                    );
                } else if ($plugin->type == 'joomla') {
                    $postdata = http_build_query(
                        array(
                            'title' => $title,
                            'user_id' => $plugin->user_id,
                            'stream_url' => $stream_url,
                            'bid' => $broadcast_id,
                            'status' => $status,
                            'key' => $auth_key,
                            'broadcast_image' => $image,
                            'description' => $description,
                            'post_id_joomla' => $broadcast->post_id_joomla,
                        )
                    );
                }

                $opts = array('http' => array(
                    'method' => 'POST',
                    'header' => array(
                        'Content-type: application/x-www-form-urlencoded',
                        "'user-agent: ". $_SERVER['HTTP_USER_AGENT']."'"
                    ),
                    'content' => $postdata,
                ),
                );

                $context = stream_context_create($opts);

                if ($plugin->type == 'wordpress') {
                    $go = $plugin->url . '?action=hpb_hp_edit_broadcast';
                } else if ($plugin->type == 'drupal') {
                    $go = $plugin->url . '?action=hpb_hp_edit_broadcast';
                } else if ($plugin->type == 'joomla') {
                    $go = $plugin->url . 'index.php?option=com_hapity&task=savebroadcast.editBroadcastData';
                }
                $this->stream_context_default();
                if (strpos($plugin->url, 'localhost') === false) {

                    $domain_available = $this->check_if_domain_is_available($plugin->url);
                    if ($domain_available == true) {

                        $result_str = @file_get_contents($go, false, $context);
                        $result = json_decode($result_str, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $matches = array();
                            $t = preg_match('/\{(.*?)\}/s', $result_str, $matches);
                            if(isset($matches[0])){
                                $result_str = $matches[0];
                                $result = json_decode($result_str, true);
                            }
                        }

                        Log::info('Broadcast: ' . $broadcast_id . ' Result: ' . json_encode($result));

                        return $result;
                    }

                }
            }
        }
    }

    public function make_plugin_call_delete($broadcast_id)
    {
        $broadcast = Broadcast::with(['user'])->where('id', $broadcast_id)->first();

        if (!is_null($broadcast) && $broadcast->id > 0) {
            $plugin = PluginId::where('user_id', $broadcast->user_id)->orderBy('id', 'DESC')->first();
            $auth_key = UserProfile::where('user_id', $broadcast->user_id)->first()->auth_key;

            if (!is_null($plugin) && $plugin->id > 0 && !empty($auth_key)) {

                if ($plugin->type == 'wordpress') {
                    $go = $plugin->url . '?action=hpb_hp_delete_broadcast&bid=' . $broadcast_id . '&key=' . $auth_key . '&post_id_wp=' . $broadcast->post_id;
                } else if ($plugin->type == 'drupal') {
                    $go = $plugin->url . '?action=hpb_hp_delete_broadcast&bid=' . $broadcast_id . '&key=' . $auth_key . '&post_id_drupal=' . $broadcast->post_id_drupal;
                } else if ($plugin->type == 'joomla') {
                    $go = $plugin->url . 'index.php?option=com_hapity&task=savebroadcast.deleteBroadcastData&bid=' . $broadcast_id . '&key=' . $auth_key . '&post_id_joomla=' . $broadcast->post_id_joomla;
                }
                $this->stream_context_default();
                if (strpos($plugin->url, 'localhost') === false) {

                    $domain_available = $this->check_if_domain_is_available($plugin->url);

                    if ($domain_available == true) {
                        $result = @file_get_contents($go);
                        Log::info('Broadcast: ' . $broadcast_id . ' Result: ' . json_encode($result));
                        return $result;
                    }

                }

            }
        }
    }

    public function stream_context_default()
    {
        stream_context_set_default([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
    }

    private function check_if_domain_is_available($host, $port = 80, $timeout = 6)
    {
        $host = parse_url($host, PHP_URL_HOST);
        $web_up = false;
        try {
            $fsock = fsockopen($host, $port, $errno, $errstr, $timeout);
            $web_up = true;
            Log::info('[ ' . json_encode($host) . ' ] Status: Up');
        } catch (Exception $ex) {
            $web_up = false;
            Log::info('[ ' . json_encode($host) . ' ] Status: Down');
        }

        return $web_up;
    }

}
