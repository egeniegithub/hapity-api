<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('ffmpeg_upload_file_path')) {
    function ffmpeg_upload_file_path($source_file_path)
    {
        $temp_pathtosave = storage_path('temp');

        if (file_exists($source_file_path)) {

            $file_name = basename($source_file_path);

            $temp_file_path = $temp_pathtosave . DIRECTORY_SEPARATOR . $file_name;            

            $ffprobe_command = "ffprobe -loglevel error -select_streams v:0 -show_entries stream_tags=rotate -of default=nw=1:nk=1 $source_file_path";
            $shell_exec = shell_exec($ffprobe_command);

            Log::channel('ffmpeg_logs')->info('ffprobe command:  ' . $ffprobe_command);
            Log::channel('ffmpeg_logs')->info('ffprobe command Result:  ' . $shell_exec);

            if ($shell_exec == 90) {
                $copy_command = 'cp ' . $source_file_path . ' ' . $temp_file_path;
                $shell_exec = shell_exec($copy_command);
                Log::channel('ffmpeg_logs')->info('command :   ' . $copy_command);

                unlink($source_file_path);

                $ffmpeg_command = 'ffmpeg -i "' . $temp_file_path . '" -vf "transpose=1,transpose=2" ' . $source_file_path;
                $shell_exec = shell_exec($ffmpeg_command);

                Log::channel('ffmpeg_logs')->info('ffmpeg command :  ' . $ffmpeg_command);

                $remove_command = 'rm ' . $temp_file_path;
                $shell_exec = shell_exec($remove_command);

                Log::channel('ffmpeg_logs')->info('rm command :  ' . $remove_command);
            }
        }
    }
}
