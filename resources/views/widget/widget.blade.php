<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://player.wowza.com/player/latest/wowzaplayer.min.js"></script>
    <style>
        h1 {
            text-align: center;
            font-family: arial;
        }
    </style>
</head>
<body>


    <h1><?php echo strtoupper($broadcast->title);?></h1>
    
    <div id="w-broadcast-{{ $broadcast->id }}" style="width:100%; height:0; padding:0 0 56.25% 0" ></div>
    @php 
                        $image_classes = '';
                        $b_image = '';
                        // $broadcast->broadcast_image;
                        $b_id = isset($broadcast->id) ? $broadcast->id : '';

                        if($broadcast->title){
                            $b_title = $broadcast->title;
                        } else {
                            $b_title = "Untitled";
                        }

                        $file_info = pathinfo($broadcast->filename);

                        $file_ext = isset($file_info['extension']) ? $file_info['extension'] : 'mp4';

                        $share_url = $broadcast->share_url;
                        $b_description = preg_replace("/\r\n|\r|\n/",'<br/>',$broadcast->description);

                        $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';
                        $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';

                        $stream_url = urlencode('https://media.hapity.com/' . $vod_app .  '/_definst_/' . $file_ext . ':' .  $broadcast->filename . '/playlist.m3u8') ;
                        if($broadcast->status == 'online') {
                            $file = pathinfo($broadcast->filename, PATHINFO_FILENAME );                                    
                            $stream_url = urlencode('https://media.hapity.com/' . $live_app . '/' .  $file . '/playlist.m3u8') ;
                        }
                        //http://[wowza-ip-address]:1935/vod/mp4:sample.mp4/playlist.m3u8
                        //rtmp%3A%2F%2F192.168.20.251%3A1935%2Flive%2F132041201998908.stream.mp4%2Fplaylist.m3u8 
                        //rtmp%3A%2F%2F192.168.20.251%3A1935%2Flive%2F132041201998908.stream%2Fplaylist.m3u8
                        //https://media.hapity.com/stage_vod/_definst_/mp4:8e192b3711cfd29cafe41297d9fa725b.stream.mp4/playlist.m3u8

                        //echo $stream_url; 

                        $status = $broadcast->status;

                        $video_file_name = $broadcast->filename;
                        
                        if(!$b_image){
                            $b_image = 'default001.jpg';
                        }

                        if($video_file_name){
                            $image_classes = 'has_video';
                        }

                    @endphp
                    @if($video_file_name)
                        <div class="video-container video-conteiner-init">
                            <div id="w-broadcast-{{ $b_id }}" style="width:100%; height:0; padding:0 0 56.25% 0"></div>
                        </div>        
                        <script>
                            WowzaPlayer.create('w-broadcast-{{ $b_id }}',
                            {
                                "license":"PLAY1-fMRyM-nmUXu-Y79my-QYx9R-VFRjJ",
                                "title":"{{ $b_title }}",
                                "description":"{{ str_replace('<br/>',' ',$b_description) }}",
                                //"sourceURL":"rtmp%3A%2F%2F52.18.33.132%3A1935%2Fvod%2F9303fbcdfa4490cc6d095988a63b44df.stream",
                                "sourceURL":"{{ $stream_url }}",
                                "posterFrameURL":"{{ $broadcast->broadcast_image }}",
                                "uiPosterFrameFillMode":"fit",
                                "autoPlay":false,
                                "volume":"75",
                                "mute":false,
                                "loop":false,
                                "audioOnly":false,
                                "uiShowQuickRewind":true,
                                "uiQuickRewindSeconds":"30"
                                }
                            );

                        </script>
                    @endif
                    
                  
      <script>
        
        var my_player = WowzaPlayer.get('w-broadcast-{{ $broadcast->id }}'); 
        playListener = function ( playEvent ) {
            var broadcast_id = '{{ $broadcast->id }}';
            var my_request;
            my_request = $.ajax({
                url: "{{ route('broadcast.update.view.count', $broadcast->id) }}",
                type: 'GET'
            });
            my_request.done(function(response){
                console.log(response);
            });
        };
        my_player.onPlay( playListener );
    </script>

</body>
</html>