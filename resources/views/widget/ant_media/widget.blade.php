<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
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

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1> {{ strtoupper($broadcast->title) }}</h1>
            </div>
            <div class="col-sm-12">
                <a data-fancybox data-src="#video_player_{{ $broadcast->id }}" href="javascript:;">                
                <div class="row">                  
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="thumbnail">
                            <img src="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . Auth::id() . '/' . $broadcast->broadcast_image) : asset('images/default001.jpg') }}" alt="" />
                        </div>
                    </div>
                </div> 
            </a>
        </div>
    <div class="col-sm-12">
        <div id="video_player_{{ $broadcast->id }}" style="display: none;" class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0; margin:0; ">
                
            @if($broadcast->status == 'online')
                <video
                    id="my_live_player_{{ $broadcast->id }}"
                    class="video-js"
                    controls
                    preload="auto"
                    poster="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . $broadcast->user_id . '/' . $broadcast->broadcast_image) : asset('images/default001.jpg') }}"
                    data-setup='{"fluid": false}'>
                    <source src="{{ ANT_MEDIA_SERVER_STAGING_URL . WEBRTC_APPEE .'/streams/' . pathinfo($broadcast->video_name, PATHINFO_FILENAME) . '.m3u8' }}" type="application/x-mpegURL"></source>
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
                    <source src="{{ ANT_MEDIA_SERVER_STAGING_URL . WEBRTC_APPEE .'/streams/' . pathinfo($broadcast->video_name, PATHINFO_FILENAME) . '.mp4' }}" type="video/mp4"></source>
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
    </div>

    </div>
</div>

    

    
    <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="{{ asset('assets/video-js-7.7.4/video.min.js') }}"></script>
    <script src="{{ asset('assets/video-js-7.7.4/videojs-http-streaming.min.js') }}"></script>

</body>
</html>