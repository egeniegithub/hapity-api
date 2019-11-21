<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('ffmpeg_upload_file_path')) {
    function ffmpeg_upload_file_path($source_file_path, $final_path = '')
    {

        if (file_exists($source_file_path)) {

            $file_name = pathinfo($source_file_path, PATHINFO_BASENAME);
            $directory_name = pathinfo($source_file_path, PATHINFO_DIRNAME);

            $temp_file_name = 'temp-' . $file_name;

            $ffprobe_command = "ffprobe -loglevel error -select_streams v:0 -show_entries stream_tags=rotate -of default=nw=1:nk=1 \"$source_file_path\"";
            $shell_exec = shell_exec($ffprobe_command);

            Log::channel('ffmpeg_logs')->info('ffprobe:  ' . $ffprobe_command);
            Log::channel('ffmpeg_logs')->info('ffprobe Result:  ' . $shell_exec);

            if (intval($shell_exec) == 90) {
                $commands = '';
                $commands .= 'cp "' . $source_file_path . '" "' . $directory_name . DIRECTORY_SEPARATOR . $temp_file_name . '"' . PHP_EOL;
                $commands .= 'ffmpeg -y -i "' . $temp_file_name . '" -vf "transpose=1,transpose=2" "' . $source_file_path . '"' . PHP_EOL;
                $commands .= 'rm "' . $directory_name . DIRECTORY_SEPARATOR . $temp_file_name . '"' . PHP_EOL;

                if (!empty($final_path)) {
                    $commands .= 'cp -f "' . $source_file_path . '" "' . $final_path . DIRECTORY_SEPARATOR . $file_name . '"' . PHP_EOL;
                    $commands .= 'rm "' . $source_file_path . '"' . PHP_EOL;
                }

                $shell_exec = shell_exec($commands);
                Log::channel('ffmpeg_logs')->info(PHP_EOL . PHP_EOL);
                Log::channel('ffmpeg_logs')->info($commands);
                Log::channel('ffmpeg_logs')->info(PHP_EOL . 'End ============================================================================================================');
            }

            //Convert Routine
            //ffmpeg -i example.mov -f mp4 -vcodec libx264 -preset fast -profile:v main -acodec aac example.mp4 -hide_banner

            /*
        $commands = 'cp "' . $source_file_path . '" "' . $temp_file_path . '"' . PHP_EOL;
        $commands .= 'rm "' . $source_file_path . '"' . PHP_EOL;
        $commands .= 'ffmpeg -i "' . $temp_file_path . '" -f mp4 -vcodec libx264 -preset fast -profile:v main -acodec aac "' . $source_file_path . '" -hide_banner' . PHP_EOL;
        $commands .= 'rm "' . $temp_file_path . '"' . PHP_EOL;
        $shell_exec = shell_exec($commands);

        Log::channel('ffmpeg_logs')->info('Convert Routine Start ============================================================================================================');
        Log::channel('ffmpeg_logs')->info($commands);
        Log::channel('ffmpeg_logs')->info('Convert Routine End ============================================================================================================');
         */
        }
    }
}
