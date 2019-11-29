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

		    $file_info = pathinfo($request['stream']);

            $file_ext = isset($file_info['extension']) ? $file_info['extension'] : 'mp4';

            $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';
            $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';

            $stream_url = urlencode('https://media.hapity.com/' . $vod_app .  '/_definst_/' . $file_ext . ':' .  $request['stream'] . '/playlist.m3u8') ;
            if($request['status'] == 'online') {
                $file = pathinfo($request['stream'], PATHINFO_FILENAME );                                    
                $stream_url = urlencode('https://media.hapity.com/' . $live_app . '/' .  $file . '/playlist.m3u8') ;
            }

            $data['stream_url'] = $stream_url;

		    return view('widget.widget',$data);
		}
		else{
			return "<h1 style='text-align:center;'>No broadcast found</h1>";
		}

    }
}
