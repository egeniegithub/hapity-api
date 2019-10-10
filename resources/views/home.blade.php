@php 
  $b_id = '';
  $status = '';
  $video_file_name = '';
  $b_image = '';
  $get_token = '';

@endphp

@extends('layouts.app')

@push('css')
  <style type="text/css" media="screen">
    .bordcast-active h3 a { cursor: default;  }
  </style>
@endpush
@section('content')
<script type="text/javascript" src="https://player.wowza.com/player/latest/wowzaplayer.min.js"></script>

    <div class="profile-page">

    <div class="clearfix"></div>

    <div class="profile-section-main">
        
    <div class="container">
            <div class="flash-error col-xs-12 col-sm-12 col-md-12" style="display: none;">Flash player is not supported by your browser, you need flash installed to see Broadcast Videos</div>
            <div class="col-xs-12 col-sm-3 col-md-3">
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
            
            <div class="col-xs-12 col-sm-9 col-md-9">
                <div class="row">
                    <div class="col-sm-12">
                        @if (Session::has('flash_message'))
                            {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                            <div class="alert alert-success">{{ Session::get('flash_message') }}</div>
                        @endif
                        @if(Session::has('flash_message_delete'))
                            {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                            <div class="alert alert-danger">{{ Session::get('flash_message_delete') }}</div>
                        @endif
                        
                    </div>
                </div>
                <div class="center-content">
                    <div class="start-broadcast">
                        <a href="{{route('webcast')}}" class=""><i class="fa fa-camera"></i> Start Your Broadcast Here</a>
                        <a href="{{route('create-content')}}" class="create-content"><i class="fa fa-plus-square "></i> Create Content</a>
                    </div>
                    <div class="my-bordcasts-container" data-user-id="{{ auth::user()->id}}">
                        @php
                            $ipArr = array(0 => '52.18.33.132', 1 => '52.17.132.36');
                            $index = rand(0,1);                            
                            $ip =  env('APP_ENV') == 'local' ? '192.168.20.251' : $ipArr[$index];

                        @endphp

                        @foreach ($broadcasts as $broadcast)
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
                                $b_description = $broadcast->description;

                                $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';
                                $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';

                                $stream_url = urlencode('https://media.hapity.com/' . $vod_app .  '/_definst_/' . $file_ext . ':' .  $broadcast->filename . '/playlist.m3u8') ;
                                if($broadcast->status == 'online') {
                                    $file = pathinfo($broadcast->filename, PATHINFO_FILENAME );                                    
                                    $stream_url = urlencode('rtmp://media.hapity.com/' . $live_app . '/' .  $file . '/playlist.m3u8') ;
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
                            <div id="bordcast-single-{{ $b_id }}" class="my-bordcast-single clearfix  {{ $image_classes }}">
                                <a href="#" class="bordcast-play image-section">
                                    @php 
                                        $thumbnail_image = $broadcast->broadcast_image;
                                        $allowedExtensions = ['png', 'jpg', 'jpeg'];

                                    // check if the data is empty 
                                    @endphp
                                    @if(!empty($thumbnail_image) && $thumbnail_image != null)
                                    @php
                                        // check base64 format
                                        $explode = explode(',', $thumbnail_image);
                                        // check if type is allowed
                                        $format = str_replace(
                                            ['data:image/', ';', 'base64'], 
                                            ['', '', '',], 
                                            $explode[0]
                                        );  
                                    @endphp
                                        @if(in_array($format, $allowedExtensions))
                                            <img src="{{ $thumbnail_image }}" alt="{{ $b_title }}" />
                                        @else
                                            <img src="{{ asset('images/broadcasts/' . Auth::id() . '/'  .$thumbnail_image) }}" alt="{{ $b_title }}" />
                                        @endif
                                        
                                    @else
                                        <img src="{{ asset('images/default001.jpg') }}" alt="{{ $b_title }}" />
                                    @endif

                                    @if($video_file_name)
                                        <div class="video-container video-conteiner-init" style="display:none;">
                                            {{-- <div class="broadcast-streaming" id="w-broadcast-{{ $b_id }}">Loading Broadcast</div> --}}

                                            <div id="w-broadcast-{{ $b_id }}" style="width:100%; height:0; padding:0 0 56.25% 0"></div>
                                        </div>                                       

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
                                </a> 
                                <div class="bordcast-inner-data">
                                    <h3 class="my-bordcast-title">
                                        <a href="#" class="bordcast-play"><?php echo $b_title; ?></a>
                                    </h3>
                                    <?php if($b_description): ?>
                                        <p class="description">
                                            <p class="short-desc">
                                                <?php echo substr($b_description, 0, 300); ?>
                                                <?php if (strlen($b_description)>300){
                                                    echo '.... <span>Load More</span>';
                                                } ?>
                                            </p>
                                            <p class="full-desc" style="display: none">
                                                {{ $b_description }}
                                            </p>
                                        </p>
                                    <?php endif; ?>    
                                    <?php if($broadcast->status == "online") : ?>
                                        <span class="broadcast-live"></span>
                                    <?php else : ?>
                                        <span class="broadcast-offline"></span>
                                    <?php endif; ?>
                                </div>
                                @php
                                    if($status == 'offline'){
                                        $stream_url = str_replace('/live/', '/vod/', $stream_url);
                                    } 
                                @endphp
                                <ul class="bordcast-edit-actions">
                                    <li class="social-share-action">
                                        <a href="#" data-toggle="modal" data-target="#share-modal"><!-- <i class="fa fa-share-alt-square"></i> -->
                                            <img src="{{ asset('assets')}}/images/share.png" width="28" alt="social Media">
                                        </a>
                                        <ul class="share-with-icons">
                                                @if($stream_url)
                                                <li>
                                                    <a href="javascript:;" data-modal-id="embed-code-popup-<?php echo $b_id;?>" class="code-icon">
                                                        <i class="fa fa-code"></i>
                                                    </a>
                                                </li>
                                                @endif
                                            <li>
                                                <a href="https://twitter.com/home?status=<?php echo $share_url; ?>" target="_blank" class="twitter">
                                                    <i class="fa fa-twitter"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a  href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank">
                                                    <i class="fa fa-facebook"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('edit_broadcast_content',$b_id) }}" data-toggle="modal"><!-- <i class="fa fa-edit"></i> -->
                                        <img src="{{ asset('assets') }}/images/edit.png" width="28" alt="Edit">
                                    </a></li>
                                    <li><a href="#" data-toggle="modal" data-broadcast-id="<?php echo $b_id; ?>"  data-broadcast-url="<?php echo $stream_url; ?>" data-target="#delete-modal" class="delete-btn"><!-- <i class="fa fa-trash-o"></i> -->
                                        <img src="{{ asset('assets') }}/images/delete.png" width="28" alt="Delete">
                                    </a></li>
                                </ul>
                                <?php if($stream_url): ?>
                                    <div id="embed-code-popup-{{ $b_id }}" class="modal-box_popup">
                                        <header> <a href="javascript:;" class="js-modal-close close">×</a>
                                            <h3>Copy & Paste below code in your website</h3>
                                        </header>
                                        <div class="modal-body">
                                            <div class="embedcode-modal-innser">
                                                <textarea readonly="">
                                                    <iframe height="600" width="100%" scrolling="no" frameborder="0" 
                                                    src="https://api.hapity.com/widget.php?stream=<?php echo $stream_url;?>&title=<?php echo urlencode($b_title);?>&status=<?php echo $broadcast->status;?>&broadcast_image=<?php echo $b_image;?>">
                                                    </iframe>
                                                </textarea>                        
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>    
                                <a href="#" class="close-btn">X</a>
                            </div>
                        @endforeach                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php //echo $hapity_footer_view; ?>
    </div>
    <div class="clear"></div>
    <!-- Modal -->
    <div id="delete-modal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
      <div class="modal-dialog align-middle">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-body">
            <h3>Are you sure you want to delete this Broadcast?</h3>
            <input type="hidden" name="delete-brodcast-id" id="brodcast-id" value="">
            <input type="hidden" name="delete-brodcast-url" id="brodcast-url" value="">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger delete-brodcast-action">Delete</button>
            <button type="button" class="btn btn-default delete-model-dismiss" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <!--including footer file-->
    
@endsection

@push('script')
   <script type="text/javascript" src="{{ asset('assets/js/infiniteScroll.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {           

            jQuery(document).on('click', '.bordcast-active .close-btn', function(event) {
                event.preventDefault();
                var bordcast = jQuery(this).parents('.my-bordcast-single');
                bordcast.removeClass('bordcast-active');
                bordcast.find('img').show();
                bordcast.find('.video-container').hide();
            });

            jQuery('.delete-btn').on('click', function(event) {
                event.preventDefault();
                jQuery('#delete-modal #brodcast-id').val(jQuery(this).data('broadcast-id'));
                jQuery('#delete-modal #brodcast-url').val(jQuery(this).data('broadcast-url'));
            });

            jQuery('.short-desc > span').on('click', function(event) {
                jQuery(this).parents('.short-desc').hide().next('.full-desc').show();
            });

            jQuery( ".social-share-action" ).hover(
                function () {
                    jQuery(this).addClass('on-hover').find('ul').stop().show();
                }, 
                function () {
                    jQuery(this).removeClass('on-hover').find('ul').stop().hide();
                }
            );
            
        });

        jQuery(document).on('click', '.delete-brodcast-action', function(event) {
            event.preventDefault();
            var delete_model = jQuery(this).parents('#delete-modal');
            var stream_id = delete_model.find('#brodcast-id').val();
            var stream_url = delete_model.find('#brodcast-url').val();
            jQuery('.delete-model-dismiss').hide();
            jQuery(this).text('Deleting...');
            $.ajax({
                url: "{{route('deleteBroadcast')}}",
                data: {
                    _token: "{{ csrf_token() }}",
                    token: '<?php echo $get_token; ?>',
                    user_id: '{{ auth::user()->id }}',
                    stream_url: stream_url,
                    stream_id: stream_id,
                    plugin_auth_key: '{{ $userdata['profile']['auth_key'] }}',
                    server: '52.18.33.132'
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data)
                    jQuery('.delete-brodcast-action').text(data.message);
                    setTimeout(function(){
                        jQuery('#bordcast-single-'+stream_id).remove();
                        jQuery('.delete-model-dismiss').trigger('click');
                        jQuery('.delete-brodcast-action').text('Delete');
                    }, 900);
                },
                type: 'POST'
            });
        });

        jQuery(document).on('click', '.bordcast-play', function(event) {
            event.preventDefault();
            var bordcast = jQuery(this).parents('.my-bordcast-single');
            bordcast_id = bordcast.attr('id');
            playOnlyOne(bordcast_id);

            jQuery('.bordcast-play img').show();
            jQuery('.bordcast-play .video-container').hide();
            if(bordcast.hasClass('has_video')){
                bordcast.find('img').hide();
                bordcast.find('.video-container').show();
            } 
            jQuery('.my-bordcast-single.bordcast-active').removeClass('bordcast-active');
            bordcast.addClass('bordcast-active');
            
        });

        function playOnlyOne(playThis) {
            for (i=0; i<document.getElementsByClassName('jwplayer').length;i++) { 
                if (document.getElementsByClassName('jwplayer')[i].id != playThis) {
                    jwplayer(document.getElementsByClassName('jwplayer')[i]).play(false);   
                } else {
                    jwplayer(playThis).play('play');
                }
            }
        }
    </script>
@endpush
