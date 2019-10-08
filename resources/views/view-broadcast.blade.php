@extends('layouts.app')

@push('css')

@endpush
@section('content')



@php
    $ipArr = array(0 => '52.18.33.132', 1 => '52.17.132.36');
    $index = rand(0,1);                            
    $ip =  env('APP_ENV') == 'local' ? '192.168.20.251' : $ipArr[$index];
@endphp

<style type="text/css" media="screen">
    .my-bordcast-single iframe {
        width: 100%;
        border:0;
        height: 600px;
    }
</style>
<div class="section-main detail-broadcast" style="padding-top: 50px;">
    <div class="container">
        <div class="flash-error col-xs-12 col-sm-12 col-md-12" style="display: none;">Flash player is not supported by your browser, you need flash installed to see Broadcast Videos</div>
        
        <div class="col-xs-12 col-sm-3 col-md-3"></div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="my-bordcast-single bordcast-active">
                   @php 
                    $image_classes = '';
                    $b_image = '';
                    $b_id = isset($broadcast->id) ? $broadcast->id : '';
                    if($broadcast->title){
                        $b_title = $broadcast->title;
                    } else {
                        $b_title = "Untitled";
                    }
                    $file_info = pathinfo($broadcast->filename);
                    $file_ext = isset($file_info['extension']) ? $file_info['extension'] : 'mp4';
                    $share_url = $broadcast->share_url;
                    $b_description = $broadcast->description;
                    $stream_url = urlencode('http://' . $ip .  ':1935/vod/' . $file_ext . ':' .  $broadcast->filename . '/playlist.m3u8') ;
                    

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
                        <script>
                            WowzaPlayer.create('w-broadcast-{{ $b_id }}',
                            {
                                "license":"PLAY1-fMRyM-nmUXu-Y79my-QYx9R-VFRjJ",
                                "title":"{{ $b_title }}",
                                "description":"{{ $b_description }}",
                                //"sourceURL":"rtmp%3A%2F%2F52.18.33.132%3A1935%2Fvod%2F9303fbcdfa4490cc6d095988a63b44df.stream",
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

                        </script>
                    @endif
                    
                    @php
                        if($status == 'offline'){
                            $stream_url = str_replace('/live/', '/vod/', $stream_url);
                        } 
                        // echo $stream_url;
                    @endphp
                    <iframe height="600" width="100%" scrolling="no" frameborder="0" 
                    src="https://api.hapity.com/widget.php?stream=<?php echo $stream_url;?>&title=<?php echo urlencode($b_title);?>&status=<?php echo $broadcast->status;?>&broadcast_image=<?php echo $b_image;?>">
                    </iframe>

               
                <?php if($broadcast['status'] == "online") : ?>
                    <span class="broadcast-live"></span>
                <?php else : ?>
                    <span class="broadcast-offline"></span>
                <?php endif; ?>
                <h3 class="my-bordcast-title"><?php echo $broadcast['title'];; ?></h3>
                <p class="description"><?php echo $broadcast['description']; ?></p>

                <ul class="post-options clearfix share-with-icons-live">
                    <li class="username">
                            @if(isset(auth::user()->profile->profile_picture) && !empty(auth::user()->profile->profile_picture))
                            <img src="{{asset('images/profile_pictures/'.auth::user()->profile->profile_picture)}}">
                          @else
                             <img src="{{ asset('assets/images/null.png') }}">
                          @endif
                        &nbsp; <span>{{ auth::user()->username }}</span>
                    </li>
                    <li><a href="javascript:;" data-modal-id="embed-code-popup-<?php echo $broadcast['id'];?>" class="code-icon"><i class="fa fa-code"></i></a></li>
                    <li class="twitter-icon"><a href="https://twitter.com/home?status=<?php echo $broadcast['share_url'] ?>" target="_blank" class="twitter"><i class="fa fa-twitter"></i></a></li>
                    <li class="facebook-icon"><a href="javascript:void(0)" onclick="fbshare('fbtest','<?php echo $broadcast['stream_url'];?>','<?php echo $broadcast['broadcast_image'];?>')"><i class="fa fa-facebook"></i></a></li>                           
                </ul>
                <div id="embed-code-popup-<?php echo $broadcast['id'];?>" class="modal-box_popup">
                    <header> <a href="javascript:;" class="js-modal-close close">×</a>
                        <h3>Copy & Paste below code in your website</h3>
                    </header>
                    <div class="modal-body">
                        <div class="embedcode-modal-innser">
                            <textarea readonly="">
                                <iframe height="600" width="100%" scrolling="no" frameborder="0" 
                                src="https://api.hapity.com/widget.php?stream={{ $broadcast['stream_url'] }}&title={{ urlencode($broadcast['title']) }}&status={{ $broadcast['status'] }}&broadcast_image={{ $broadcast['broadcast_image'] }}">
                                </iframe>
                            </textarea>                        
                        </div>
                    </div>
                </div>
                <div class="social-like-btn">
                    

            </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3"></div>
    </div>

</div>

@endsection

@push('script')

<script type="text/javascript">
    
    $(document).ready(function(){
        var channel = pusher.subscribe('comments-<?php echo $broadcast['id'];?>');
        channel.bind('new_comment', displayComment);
    });

</script>

@endpush