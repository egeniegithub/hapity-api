<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WidgetController extends Controller
{
    
    public function index(Request $request){
    	$data['b_description'] = '';
    	if(isset($request['stream'])){
	    	$data['b_id'] = isset($request['bid']) ? $request['bid'] : '';  

	        $data['b_title'] = isset($request['title']) ? $request['title'] : 'Untitled';
	        $data['b_description  '] = isset($request['description']) ? $request['description'] : '';

	        if(isset($request['broadcast_image']) && $request['broadcast_image']!=''){
			        $data['broadcast_image'] = $request['broadcast_image'];
		    }else{
		        $data['broadcast_image'] = public_path('images/default001.jpg');
		    }
		    $stream = $request['stream'];
		    $stream = str_replace("rtsp", "rtmp", $stream);
		    // Temporary change IP address
		    $stream = str_replace("52.17.132.36", "52.18.33.132", $stream);
		    // Remove Port
		    $stream = str_replace(":1935", "", $stream);
		    // Replace Ip Address With SubDomain
		    $stream = str_replace(["52.17.132.36", "52.18.33.132"], 'media.hapity.com', $stream);
		    if($request['status'] != 'online'){
		        $stream = str_replace("live", "vod", $stream);
		    }
		    $data['stream_url'] = urlencode(str_replace(array("rtmp", "rtsp"), "https", $stream).'/playlist.m3u8');



	       //  $file_info = pathinfo($request['stream_url']);

	       //  $file_ext = isset($file_info['extension']) ? $file_info['extension'] : 'mp4';

	       //  $data['b_description'] = isset($request['description']) ? $request['description'] : '';

	       //  $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';
	       //  $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';
	       //  $vod_app = 'stage_vod';
	       //  $live_app = 'stage_live';

	       //  $stream_url = urlencode('https://media.hapity.com/' . $vod_app .  '/_definst_/' . $file_ext . ':' .  $request['stream'] . '/playlist.m3u8') ;
	       //  if($request['status'] == 'online') {
	       //      $file = pathinfo($request['stream'], PATHINFO_FILENAME );                                    
	       //      $stream_url = urlencode('https://media.hapity.com/' . $live_app . '/' .  $file . '/playlist.m3u8') ;
	       //  }
	       // // $data['stream_url'] = $stream_url;
	       // dd($data,$stream_url);
		    return view('widget.widget',$data);
		}
		else{
			return "<h1>No broadcast found</h1>";
		}

    }
}
