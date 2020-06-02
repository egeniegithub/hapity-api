@extends('layouts.app')
@section('content')   
<script type="text/javascript" src="https://player.wowza.com/player/latest/wowzaplayer.min.js"></script>

    <div class="container">    
        <br />       
        
        <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="profile-section-disable">
                    <div class="profile-picture">
                        <figure>
                          @if(isset(Auth::user()->profile->profile_picture) && !empty(Auth::user()->profile->profile_picture))
                            <img src="{{asset('images/profile_pictures/'.Auth::user()->profile->profile_picture)}}">
                          @else
                             <img src="{{ asset('assets/images/null.png') }}">
                          @endif
                        </figure>
                        <div class="text">
                            <h2>
                                <a href="{{route('settings')}}">
                                  @if(Auth::user())
                                    {{ Auth::user()->username }}
                                  @endif
                                </a>
                            </h2>
                        </div>
                    </div>
               
                </div>
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">     
                @if(Session::has('message_success')) 
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Success!</strong> {{ Session::get('message_success')}}
                            </div>
                        </div>
                    </div>                
                @endif
                @if(Session::has('message_error')) 
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Error!</strong> {{ Session::get('message_error')}}
                            </div>
                        </div>
                    </div>                
                @endif

                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('broadcasts.create') }}" class="btn btn-block btn-hapity-dark btn-lg">
                            <i class="fa fa-camera"></i> Start Your Broadcast Here
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('broadcasts.upload') }}" class="btn btn-block btn-hapity-dark btn-lg">
                            <i class="fa fa-plus-square "></i> Create Content
                        </a>
                    </div>
                </div>

                <hr />
                @foreach($broadcasts as $broadcast_key => $broadcast)    
                    @if($broadcast['file_exists'])
                        <a data-fancybox data-src="#video_player_{{ $broadcast->id }}" href="javascript:;">                
                            <div class="row">                  
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="panel panel-success panel-broadcast">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                    <div class="thumbnail">
                                                        <img src="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . Auth::id() . '/' . $broadcast->broadcast_image) : asset('images/default001.jpg') }}" alt="" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                    <h3 class="broadcast-title">{{ $broadcast->title }}</h3>
                                                    @if(!empty($broadcast->description))
                                                    <p class="short-desc">{{ $broadcast->description }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-right">
                                                    <ul class="bordcast-edit-actions">
                                                        <li class="social-share-action">
                                                            <a href="#" data-toggle="modal" data-target="#share-modal">
                                                                <img src="{{ asset('assets') }}/images/share.png" alt="social Media" width="28">
                                                            </a>
                                                            <ul class="social-share-on" style="display: none;">
                                                                <li>
                                                                    <a href="javascript:;" data-modal-id="embed-code-popup-{{ $broadcast->id }}" class="code-icon">
                                                                        <i class="fa fa-code"></i>
                                                                    </a>
                                                                </li>                                                                                                                
                                                                <li>
                                                                    <a href="https://twitter.com/intent/tweet?url={{ $broadcast->share_url }}" target="_blank" class="twitter">
                                                                        <i class="fa fa-twitter"></i>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $broadcast->share_url }}" target="_blank">
                                                                        <i class="fa fa-facebook"></i>
                                                                    </a>
                                                                </li>
                                                                
                                                            </ul>
                                                        </li>
                                                        <li class="social-share-action">
                                                            <a href="{{ route('broadcasts.edit', [$broadcast->id]) }}">
                                                                <img src="{{ asset('assets') }}/images/edit.png" alt="Edit" width="28">
                                                            </a>
                                                        </li>
                                                        <li class="social-share-action">
                                                            <a href="javascript:void();" data-broadcast_id="{{ $broadcast->id }}" class="delete-btn">
                                                                <img src="{{ asset('assets') }}/images/delete.png" alt="Delete" width="28">
                                                            </a>
                                                            <form id="form_delete_broadcast_{{ $broadcast->id }}" enctype="multipart/form-data" method="POST" action="{{ route('broadcasts.delete') }}">
                                                                {{ csrf_field() }}
                                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_broadcast" />
                                                                <input type="hidden" id="broadcast_id" name="broadcast_id" value="{{ $broadcast->id }}" />                                                         
                                                            </form>
                                                        </li>
                                                    </ul>

                                                    <div id="embed-code-popup-{{ $broadcast->id }}" class="modal-box_popup">
                                                        <header> <a href="javascript:;" class="js-modal-close close">×</a>
                                                            <h3 class="text-left">Copy & Paste below code in your website</h3>
                                                        </header>
                                                        <div class="modal-body">
                                                            <div class="embedcode-modal-innser">
                                                                <textarea readonly="">
                                                                    <iframe height="600" width="100%" scrolling="no" frameborder="0" 
                                                                src="{{ route('widget.index') }}?stream={{ $broadcast->video_name }}&title={{ $broadcast->title }}&status={{ $broadcast->status }}&bid={{ $broadcast->id }}&broadcast_image={{ $broadcast->broadcast_image }}&user_id={{ $broadcast->user_id }}">
                                                                    </iframe>
                                                                </textarea>                        
                                                            </div>
                                                        </div>
                                                    </div>
                                        
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </a>
                    
                        <div id="video_player_{{ $broadcast->id }}" style="display: none;" class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0; margin:0; ">
                            @if($broadcast->is_antmedia)        
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
                                        id="my_recorded_player_{{ $broadcast->id }}"
                                        class="video-js vjs-big-play-centered"
                                        controls
                                        preload="auto"
                                        poster="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . Auth::id() . '/' . $broadcast->broadcast_image) : asset('images/default001.jpg') }}"
                                        data-setup='{"fluid": false}'>
                                        <source src="{{ ANT_MEDIA_SERVER_STAGING_URL . WEBRTC_APP . '/streams/' . pathinfo($broadcast->video_name, PATHINFO_FILENAME) . '.mp4' }}" type="video/mp4"></source>
                                        <p class="vjs-no-js">
                                            To view this video please enable JavaScript, and consider upgrading to a
                                            web browser that
                                            <a href="https://videojs.com/html5-video-support/" target="_blank">
                                            supports HTML5 video
                                            </a>
                                        </p>
                                    </video>
                                @endif
                            @else
                                <div class="video-container video-conteiner-init">
                                    <div id="w-broadcast-{{ $broadcast->id }}" style="width:700px; height:0; padding:0 0 56.25% 0"></div>
                                </div>
                                <script>
                                    WowzaPlayer.create('w-broadcast-{{ $broadcast->id }}',
                                    {
                                        "license":"PLAY1-fMRyM-nmUXu-Y79my-QYx9R-VFRjJ",
                                        "title":"{{ $broadcast->title }}",
                                        "description":"{{ $broadcast->description }}",
                                        //"sourceURL":"rtmp%3A%2F%2F52.18.33.132%3A1935%2Fvod%2F9303fbcdfa4490cc6d095988a63b44df.stream",
                                        "sourceURL":"{{ $broadcast->dynamic_stream_url_web }}",
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
                            </div>
                        </div>
                              
                    @endif
                @endforeach                              
            </div>
        </div>
       
        <br />
        <br />
        <br />
    </div>
    
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    
    <link href="{{ asset('assets/video-js-7.7.4/video-js.min.css') }}" rel="stylesheet" />
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="{{ asset('assets/video-js-7.7.4/video.min.js') }}"></script>
    <script src="{{ asset('assets/video-js-7.7.4/videojs-http-streaming.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            function closeModel(id){
                var my_player = WowzaPlayer.get('w-broadcast-'+id);
                if(my_player !== null){
                    my_player.finish();
                }
                console.log(my_player);
            }
            $('body').on('click', '.delete-btn', function(){
                var broadcast_id = $(this).attr('data-broadcast_id');
                alertify.confirm(
                    'Are you sure?', 
                    'Are you sure you want to delete this broadcast?', 
                    function(){
                        $('#form_delete_broadcast_' + broadcast_id).submit();
                    }, 
                    function(){
                        alertify.error('Cancelled');
                    });
            });

            $(".social-share-action").hover(
                function () {
                    $(this).addClass('on-hover').find('ul').stop().show();
                }, 
                function () {
                    $(this).removeClass('on-hover').find('ul').stop().hide();
                }
            );
            
        });

        function closeModel(id){
            var my_player = 'w-broadcast-'+id;
            console.log(my_player);
        }

    </script>
@endpush





