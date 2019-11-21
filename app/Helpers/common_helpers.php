<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('ffmpeg_upload_file_path')) {
    function ffmpeg_upload_file_path($source_file_path)
    {
        $temp_pathtosave = storage_path('temp');

        if (file_exists($source_file_path)) {

            $file_name = basename($source_file_path);

            $temp_file_path = $temp_pathtosave . DIRECTORY_SEPARATOR . $file_name;

            $ffprobe_command = "ffprobe -loglevel error -select_streams v:0 -show_entries stream_tags=rotate -of default=nw=1:nk=1 \"$source_file_path\"";
            $shell_exec = shell_exec($ffprobe_command);

            Log::channel('ffmpeg_logs')->info('ffprobe:  ' . $ffprobe_command);
            Log::channel('ffmpeg_logs')->info('ffprobe Result:  ' . $shell_exec);

            if (intval($shell_exec) == 90) {
                $commands = 'cp "' . $source_file_path . '" "' . $temp_file_path . '"' . PHP_EOL;
                $commands .= 'rm "' . $source_file_path . '"' . PHP_EOL;
                $commands .= 'ffmpeg -i "' . $temp_file_path . '" -vf "transpose=1,transpose=2" "' . $source_file_path . '"' . PHP_EOL;
                $commands .= 'rm "' . $temp_file_path . '"' . PHP_EOL;
                
                $shell_exec = shell_exec($commands);
                Log::channel('ffmpeg_logs')->info('Start ============================================================================================================');
                Log::channel('ffmpeg_logs')->info($commands);
                Log::channel('ffmpeg_logs')->info('End ============================================================================================================');
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
