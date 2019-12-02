
<?php
    if(isset($_GET['stream'])){
        if(isset($_GET['broadcast_image']) && $_GET['broadcast_image']!=''){
            $image = urldecode($_GET['broadcast_image']);
        }
        else{
            $image = 'default001.jpg';
        }
        $stream = $_GET['stream'];
        $title = isset($_GET['title']) ? urldecode($_GET['title']) : 'Untitle';
        $description = isset($_GET['description']) ? $_GET['description'] : '';
        $id = isset($_GET['bid']) ? $_GET['bid'] : 0;
        $status = isset($_GET['status']) ? urldecode($_GET['status']) : 'offline';
        $stream = urldecode($_GET['stream']);
        $stream = str_replace('/playlist.m3u8', '', $stream);
        if(str_replace('mp4:', '', $stream)){
            $stream = str_replace('mp4:', '', $stream);
        }

        $data = pathinfo($stream);

        $file_name = $data['basename'];

        $host_name = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

        $widget = "$host_name/widget?stream=$file_name&title=$title&status=$status&bid=$id&broadcast_image=$image&description=$description";

        header("Location: $widget");
        
    }
 
   