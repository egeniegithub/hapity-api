<?php

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
