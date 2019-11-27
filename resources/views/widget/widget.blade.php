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


    <h1><?php echo strtoupper($b_title);?></h1>
    <div id="broadcast-<?php echo $b_id;?>"><h1>Loading Stream</h1></div>

      <script>
        WowzaPlayer.create('w-broadcast-{{ $b_id }}',
        {
            "license":"PLAY1-fMRyM-nmUXu-Y79my-QYx9R-VFRjJ",
            "title":"{{ $b_title }}",
            "description":"{{ $b_description }}",
            "sourceURL":"{{ $stream_url }}",
            "autoPlay":false,
            "volume":"75",
            "mute":false,
            "loop":false,
            "audioOnly":false,
            "uiShowQuickRewind":true,
            "uiQuickRewindSeconds":"30"
            }
        );
        var my_player = WowzaPlayer.get('w-broadcast-{{ $b_id }}'); 
        playListener = function ( playEvent ) {
            var broadcast_id = '{{ $b_id }}';
            var my_request;
            my_request = $.ajax({
                url: "{{ route('broadcast.update.view.count', $b_id) }}",
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