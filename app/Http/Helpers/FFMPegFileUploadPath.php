<?php

  function ffmpeg_upload_file_path($source_file_path){
    $temp_pathtosave =  storage_path('temp');

    if(file_exists($source_file_path)) {
        $file_path_info = pathinfo($source_file_path);

        $file_name = $file_path_info['basename'];

        $temp_file_path = $temp_pathtosave . DIRECTORY_SEPARATOR . $file_name;

        $shell_exec = shell_exec('cp ' . $source_file_path . ' ' . $temp_file_path);
        
        $shell_exec = shell_exec("ffprobe -loglevel error -select_streams v:0 -show_entries stream_tags=rotate -of default=nw=1:nk=1 $temp_pathtosave");
        if($shell_exec == 90){
            unlink($source_file_path);
            $shell_exec = shell_exec('ffmpeg -i "'.$temp_pathtosave.'" -vf "transpose=1,transpose=2" '. $source_file_path);
            shell_exec('rm '.$temp_pathtosave);
        }
    }
}
