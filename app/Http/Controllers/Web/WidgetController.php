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
		    $data['stream_url'] = urlencode(str_replace(array("rtmp", "rtsp"), "https", $stream));

		    return view('widget.widget',$data);
		}
		else{
			return "<h1 style='text-align:center;'>No broadcast found</h1>";
		}

    }
}
