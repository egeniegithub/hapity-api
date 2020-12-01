<?php

use App\PluginId;
use Illuminate\Support\Facades\Log;

if (!function_exists('ffmpeg_upload_file_path')) {
    function ffmpeg_upload_file_path($source_file_path, $final_path = '')
    {

        if (file_exists($source_file_path)) {

            $file_name = pathinfo($source_file_path, PATHINFO_BASENAME);
            $directory_name = pathinfo($source_file_path, PATHINFO_DIRNAME);

            $ffprobe_command = "ffprobe -loglevel error -select_streams v:0 -show_entries stream_tags=rotate -of default=nw=1:nk=1 \"$source_file_path\"";
            $shell_exec = shell_exec($ffprobe_command);

            Log::channel('ffmpeg_logs')->info('ffprobe:  ' . $ffprobe_command);
            Log::channel('ffmpeg_logs')->info('ffprobe Result:  ' . $shell_exec);

//            if (intval($shell_exec) == 90) {
                $commands = '';
                $commands .= 'ffmpeg -y -i "' . $source_file_path . '" -vf "transpose=1,transpose=2" -f mp4 -vcodec libx264 -preset fast -profile:v main -acodec aac "' . $final_path . '" -hide_banner &> /dev/null' . PHP_EOL;

                $shell_exec = shell_exec($commands);
                Log::channel('ffmpeg_logs')->info(PHP_EOL . PHP_EOL);
                Log::channel('ffmpeg_logs')->info($commands);
                Log::channel('ffmpeg_logs')->info(PHP_EOL . 'End ============================================================================================================');
//            } else {
//                $commands = '';
//                $commands .= 'ffmpeg -i "' . $source_file_path . '" -f mp4 -vcodec libx264 -preset fast -profile:v main -acodec aac "' . $final_path . '" -hide_banner &> /dev/null' . PHP_EOL;
//
//                $shell_exec = shell_exec($commands);
//                Log::channel('ffmpeg_logs')->info(PHP_EOL . PHP_EOL);
//                Log::channel('ffmpeg_logs')->info($commands);
//                Log::channel('ffmpeg_logs')->info(PHP_EOL . 'End ============================================================================================================');
//
//            }
        }
    }
}


if(!function_exists('post_url_for_admin_broadcast')){
    function post_url_for_admin_broadcast($user_id,$broadcast_id,$share_url){
        $share_url = '';
        $post_share_url = PluginId::where('user_id',$user_id)->orderBy('id','DESC')->first();
        if(!empty($post_share_url)){
            $url = parse_url($post_share_url->url);
            $post_url = $url['scheme'].'://'.$url['host'];
            $post_type = $post_share_url->type;
            if(!empty($post_url) && !empty($post_type)){
                if($post_type == 'wordpress'){
                    $share_url = $post_url.'/?p='.$broadcast_id;
                }elseif($post_type == 'drupal'){
                    $share_url = $post_url.'/node'.'/'.$broadcast_id;
                }elseif($post_type == 'joomla'){
                    $share_url = $post_url.'/?post='.$broadcast_id;
                }
            }else{
                $share_url = !empty($share_url) ? $share_url : route('broadcast.view', $broadcast_id);
            }
        }else{
            $share_url = !empty($share_url) ? $share_url : route('broadcast.view', $broadcast_id);
        }
        return $share_url;
    }
}

if (!function_exists('check_file_exist')) {
    function check_file_exist($broadcast, $wowza_path)
    {
        $ext = pathinfo($broadcast->filename, PATHINFO_EXTENSION);

        $file_ext = 'mp4';
        switch ($ext) {
            case 'stream':
                $file_ext = 'stream.mp4';
                break;
            case 'stream_160p':
                $file_ext = 'stream_160p.mp4';
                break;
            case 'stream_360p':
                $file_ext = 'stream_360p.mp4';
                break;
            case 'stream_720p':
                $file_ext = 'stream_720p.mp4';
                break;
            case 'mp4':
            default:
                $file_ext = 'mp4';
                break;
        }

        $filename = pathinfo($broadcast->filename, PATHINFO_FILENAME);

        $filename_normal = $file_ext == 'mp4' ? $filename . '.' . $ext : $filename . '.' . $file_ext;
        $filename_160p = $file_ext == 'mp4' ? $filename . '_160p.' . $ext : $filename . '.' . $file_ext;
        $filename_360p = $file_ext == 'mp4' ? $filename . '_360p.' . $ext : $filename . '.' . $file_ext;
        $filename_720p = $file_ext == 'mp4' ? $filename . '_720p.' . $ext : $filename . '.' . $file_ext;

        $filepath_normal = $wowza_path . $filename_normal;
        $filepath_160p = $wowza_path . $filename_160p;
        $filepath_360p = $wowza_path . $filename_360p;
        $filepath_720p = $wowza_path . $filename_720p;

        $file_exists_normal = file_exists($filepath_normal) ? true : false;
        $file_exists_160p = file_exists($filepath_160p) ? true : false;
        $file_exists_360p = file_exists($filepath_360p) ? true : false;
        $file_exists_720p = file_exists($filepath_720p) ? true : false;

        $broadcast['file_normal'] = $filename_normal;
        $broadcast['file_normal_exists'] = $file_exists_normal;

        $broadcast['file_160p'] = $filename_160p;
        $broadcast['file_160p_exists'] = $file_exists_160p;

        $broadcast['file_360p'] = $filename_360p;
        $broadcast['file_360p_exists'] = $file_exists_360p;

        $broadcast['file_720p'] = $filename_720p;
        $broadcast['file_720p_exists'] = $file_exists_720p;

        $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';
        $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';

        if ($file_exists_720p == true) {
            $stream_file = $filename_720p;
        } else if ($file_exists_360p == true) {
            $stream_file = $filename_360p;
        } else if ($file_exists_160p == true) {
            $stream_file = $filename_160p;
        } else {
            $stream_file = $filename_normal;
        }

        $broadcast['file_exists'] = $file_exists_160p || $file_exists_360p || $file_exists_720p || $file_exists_normal || $broadcast->status == 'online' ? true : false;

        $stream_url = urlencode('https://media.hapity.com/' . $vod_app . '/' . $ext . ':' . $stream_file . '/playlist.m3u8');
        $stream_url_mobile = 'http://52.18.33.132:1935/' . $vod_app . '/' . $ext . ':' . $stream_file . '/playlist.m3u8';

        if ($broadcast->status == 'online') {
            $stream_url = urlencode('https://media.hapity.com/' . $live_app . '/' . $filename . '/playlist.m3u8');
            $stream_url_mobile = 'https://media.hapity.com/' . $live_app . '/' . $filename . '/playlist.m3u8';
        }

        $broadcast['dynamic_stream_url_web'] = $stream_url;
        $broadcast['dynamic_stream_url_mobile'] = $stream_url_mobile;

        return $broadcast;
    }
}
function getBroadcastThumbnail($broadcast){
    $broadcast_key = $broadcast->filename;
    $broadcast_key = str_replace("_720p.mp4","",$broadcast_key);
    $broadcast_key = str_replace(".mp4","",$broadcast_key);
    if($broadcast->resolution){
        $thumbnail_url = ANT_MEDIA_SERVER_STAGING_URL . WEBRTC_APP."/previews/".$broadcast_key.".png";
    }else{
        $thumbnail_url = ANT_MEDIA_SERVER_STAGING_URL . ADAPTIVE_APP."/previews/".$broadcast_key.".png";
    }
    //$headers = @get_headers($thumbnail_url);

    // Use condition to check the existence of URL
    // if($headers && strpos( $headers[0], '200')) {
    //     return $thumbnail_url;
    // } else{
    //     return asset('images/default001.jpg');
    // }
    if(file_exists(base_path("antmedia_store" . "/previews/" . $broadcast_key.".png"))){
        return $thumbnail_url;
    }else{
        return asset('images/default001.jpg');
    }
}
