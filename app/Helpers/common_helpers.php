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