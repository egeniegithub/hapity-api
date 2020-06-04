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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <link href="{{ asset('assets/video-js-7.7.4/video-js.min.css') }}" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <style>
        .section-main{
            padding-left: 0px !important;
            padding-right: 0px !important;
            padding-bottom: 0px !important;
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
        .section-main{
            padding: 0px;
        }
    </style>
@endpush
@section('content')

@php

@endphp

<style type="text/css" media="screen">
    .my-bordcast-single iframe {
        width: 100%;
        border:0;
        height: 600px;
    }
    .detail-broadcast{
        margin-bottom: 80px;
    }
</style>
<div class="section-main detail-broadcast" style="">
    <div class="container">
        <div class="flash-error col-xs-12 col-sm-12 col-md-12" style="display: none;">Flash player is not supported by your browser, you need flash installed to see Broadcast Videos</div>
        
        <div class="col-xs-12 col-sm-3 col-md-3"></div>
        <div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">

            <div class="my-bordcast-single bordcast-active">
                 @if($broadcast)
                        <a data-fancybox data-src="#video_player_{{ $broadcast->id }}" href="javascript:;">                
                            <div class="row">                  
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="thumbnail">
                                        <img src="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . $broadcast->user_id . '/' . $broadcast->broadcast_image) : asset('images/default001.jpg') }}" alt="" />
                                    </div>
                                </div>
                            </div> 
                        </a>
                    
                        <div id="video_player_{{ $broadcast->id }}" style="display: none;" class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0; margin:0; ">
                                    
                                @if($broadcast->status == 'online')
                                    <video
                                        id="my_live_player_{{ $broadcast->id }}"
                                        class="video-js vjs-big-play-centered"
                                        controls
                                        preload="auto"
                                        poster="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . Auth::id() . '/' . $broadcast->broadcast_image) : asset('images/default001.jpg') }}"
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
                                        style="max-width:720px;max-height:720px;"
                                        id="my_recorded_player_{{ $broadcast->id }}"
                                        class="video-js vjs-big-play-centered"
                                        controls
                                        preload="auto"
                                        poster="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . Auth::id() . '/' . $broadcast->broadcast_image) : asset('images/default001.jpg') }}"
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
                                src="{{ route('widget.index') }}?stream={{ $broadcast['filename'] }}&title={{ urlencode($broadcast['title']) }}&status={{ $broadcast['status'] }}&bid={{ $broadcast['id'] }}&broadcast_image={{ $broadcast['broadcast_image'] }}">
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
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="{{ asset('assets/video-js-7.7.4/video.min.js') }}"></script>
    <script src="{{ asset('assets/video-js-7.7.4/videojs-http-streaming.min.js') }}"></script>
@endpush