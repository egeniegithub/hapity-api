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

      <script>
        WowzaPlayer.create('w-broadcast-{{ $broadcast->id }}',
        {
            "license":"PLAY1-fMRyM-nmUXu-Y79my-QYx9R-VFRjJ",
            "title":"{{ $broadcast->title }}",
            "description":"{{ $broadcast->description }}",
            "sourceURL":"{{ $broadcast->stream_url }}",
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