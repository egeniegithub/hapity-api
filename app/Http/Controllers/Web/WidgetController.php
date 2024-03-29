<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WidgetController extends Controller
{

    public function index(Request $request)
    {

        $data['b_description'] = '';
        if (isset($request['stream'])) {
            $user_id = isset($request['user_id']) ? $request['user_id'] : '0';

            $status = $request['status'];

            $data['b_id'] = isset($request['bid']) ? $request['bid'] : '';
            $broadcast = Broadcast::find($data['b_id']);

            $wowza_path = base_path('wowza_store') . DIRECTORY_SEPARATOR;
            $broadcast = check_file_exist($broadcast,$wowza_path);

            if (!is_null($broadcast)) {
                $status = $broadcast->status;
                $user_id = $broadcast->user_id;
            }

            $data['b_title'] = isset($request['title']) ? $request['title'] : 'Untitled';
            $data['b_description  '] = isset($request['description']) ? $request['description'] : '';

            if ($user_id > 0 && isset($request['broadcast_image']) && $request['broadcast_image'] != '') {
                $data['broadcast_image'] = asset('images/broadcasts/' . $user_id . '/' . $broadcast->broadcast_image);
            } else {
                $data['broadcast_image'] = asset('images/default001.jpg');
            }

            // $file_info = pathinfo($request['stream']);

            // $file_ext = isset($file_info['extension']) ? $file_info['extension'] : 'mp4';

            // $file_name = $request['stream'];
            // if ($file_ext == 'stream' || $file_ext == 'stream_160p' || $file_ext == 'stream_360p') {
            //     $file_name = $request['stream'] . '.mp4';
            //     $file_ext = 'mp4';
            // }

            // $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';
            // $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';

            // $stream_url = urlencode('https://media.hapity.com/' . $vod_app . '/_definst_/' . $file_ext . ':' . $file_name . '/playlist.m3u8');

            // if ($status == 'online') {
            //     $file = pathinfo($request['stream'], PATHINFO_FILENAME);
            //     $stream_url = urlencode('https://media.hapity.com/' . $live_app . '/' . $file . '/playlist.m3u8');
            // }
                
            $data['stream_url'] = $broadcast->dynamic_stream_url_web;
            // dd($data);
            return view('widget.widget', $data);
        } else {
            return "<h1 style='text-align:center;'>No broadcast found</h1>";
        }

    }
}
