<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <link href="{{ asset('assets/video-js-7.7.4/video-js.min.css') }}" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <style>
        h1 {
            text-align: center;
            font-family: arial;
        }
        .thumbnail{
            text-align: center;
        }
        #my_live_player_57{
            width: 100%;
        }
        .my_live_player_57-dimensions{
            width: 100% !important;
            min-height: 500px !important;
        }
        #video_player_57{
            width: 100%;
        }

        .my_recorded_player_57-dimensions{
            width: 100% !important;
            min-height: 500px !important;
        }
    </style>
</head>
<body>

    <div style="width:100%;max-width:100%;">
        @if($broadcast->status == 'online')
                <video
                    id="my_live_player_{{ $broadcast->id }}"
                    class="video-js"
                    controls
                    preload="auto"
                    poster="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . $broadcast->user_id . '/' . $broadcast->broadcast_image) : asset('images/default001.jpg') }}"
                    data-setup='{"fluid": false}'>
                    <source src="{{ ANT_MEDIA_SERVER_STAGING_URL . WEBRTC_APP .'/streams/' . pathinfo($broadcast->video_name, PATHINFO_FILENAME) . '.m3u8' }}" type="application/x-mpegURL"></source>
                    <p class="vjs-no-js">
                        To view this video please enable JavaScript, and consider upgrading to a
                        web browser that
                        <a href="https://videojs.com/html5-video-support/" target="_blank">
                        supports HTML5 video
                        </a>
                    </p>
                </video>
            @else 
                <video
                    id="my_recorded_player_{{ $broadcast->id }}"
                    class="video-js"
                    controls
                    preload="auto"
                    poster="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . $broadcast->user_id . '/' . $broadcast->broadcast_image) : asset('images/default001.jpg') }}"
                    data-setup='{"fluid": false}'>
                    <source src="{{ ANT_MEDIA_SERVER_STAGING_URL . WEBRTC_APP .'/streams/' . pathinfo($broadcast->video_name, PATHINFO_FILENAME) . '.mp4' }}" type="video/mp4"></source>
                    <p class="vjs-no-js">
                        To view this video please enable JavaScript, and consider upgrading to a
                        web browser that
                        <a href="https://videojs.com/html5-video-support/" target="_blank">
                        supports HTML5 video
                        </a>
                    </p>
                </video>
            @endif
    </div>
</div>

    

    
    <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/video-js-7.7.4/video.min.js') }}"></script>
    <script src="{{ asset('assets/video-js-7.7.4/videojs-http-streaming.min.js') }}"></script>

</body>
</html>