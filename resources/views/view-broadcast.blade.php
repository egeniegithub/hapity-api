@extends('layouts.app')
@push('sharing_post_card')
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@gohapity" />
    <meta name="twitter:creator" content="@gohapity" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{ $broadcast->title }}" />
    <meta name="twitter:description" content="{{ $broadcast->description }}" />
    <meta name="twitter:image" content="{{ $broadcast->broadcast_image }}" />
    <meta name="twitter:url" content="{{ $broadcast->share_url }}" />

    <meta property="og:url" content="{{ $broadcast->share_url }}" />
    <meta property="og:title" content="{{ $broadcast->title }}" />
    <meta property="og:description" content="{{ $broadcast->description }}" />
    <meta property="og:image" content="{{ $broadcast->broadcast_image }}" />
    <meta property="og:type" content="Broadcast" />
    <meta property="og:local" content="en_US" />
    <meta property="article:section" content="brooadcast" />
    <meta property="article:published_time" content="{{ date("d M Y, h:a:s", strtotime($broadcast->created_at)) }}" />
    <meta property="article:modified_time" content="{{ date("d M Y, h:a:s ", strtotime($broadcast->created_at)) }}" />

    <!--  Non-Essential, But Recommended -->
    <meta property="og:site_name" content="Hapity.com">    
    <!--  Non-Essential, But Required for Analytics -->
    <meta property="fb:app_id" content="1412967295699368" />


@endpush
@push('css')


@endpush
@section('content')

<script type="text/javascript" src="https://player.wowza.com/player/latest/wowzaplayer.min.js"></script>

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
                                "description":"{{ str_replace("<br/>"," ",$b_description) }}",
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
                    
                  
                    
               
                <?php if($broadcast['status'] == "online") : ?>
                    <span class="broadcast-live"></span>
                <?php else : ?>
                    <span class="broadcast-offline"></span>
                <?php endif; ?>
                <h3 class="my-bordcast-title"><?php echo $broadcast['title'];; ?></h3>
                <p class="description"><?php echo $broadcast['description']; ?></p>

                <ul class="post-options clearfix share-with-icons-live">
                    <li class="username">
                        @if(!is_null($broadcast->user) && !empty($broadcast->user->profile->profile_picture))
                            <img src="{{asset('images/profile_pictures/' . $broadcast->user->profile->profile_picture)}}" />
                        @else
                            <img src="{{ asset('assets/images/null.png') }}" />
                        @endif

                        &nbsp; <span>{{ $broadcast->user->username }}</span>
                    </li>
                    <li>
                        <a href="javascript:;" data-modal-id="embed-code-popup-{{ $broadcast->id }}" class="code-icon">
                            <i class="fa fa-code"></i>
                        </a>
                    </li>
                    <li class="twitter-icon">
                        <a href="https://twitter.com/intent/tweet?url={{ $broadcast->share_url }}" target="_blank" class="twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </li>
                    <li class="facebook-icon"> 
                        <a  href="https://www.facebook.com/sharer/sharer.php?u={{ $broadcast->share_url }}" target="_blank">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>                           
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


@endpush