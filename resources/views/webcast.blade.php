@extends('layouts.app')

@push('css')
    <style type="text/css">
        .preview-image{
            max-width: 100% !important;
        }
        .live-streaming{
            min-height: 760px !important;
        }
    </style>
@endpush
@section('content')

@php $time = time(); @endphp

<div class="profile-page new_design webcast-page-wrapper">
    <div class="live-streaming">
        <div class="stream-conatiner" id="stream-conatiner-wrp">
            <h3 class="braodcast-web-heading">Broadcast for web</h3>
            <div class="flash-error webcast-page-flash-error col-xs-12 col-sm-12 col-md-12" style="display: none;">Flash player is not supported by your browser, you need flash installed to see Broadcast Videos</div>
            <div class="streamimg-logo"><img src="{{asset('assets')}}/images/broadcast-icon-new.png" width="100"></div>
            <div class="live-broadcast-form">

                <form enctype="multipart/form-data">
                        <div class="form-group clearfix">
                            <input type="text" class="broadcast-title" name="" placeholder="ENTER BROADCAST TITLE" id="title" required autocomplete="off">
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group label-cstm">
                                  
                                    <div class="styled-input-single">
                                        <input type="checkbox" name="fieldset-1" id="senstive-info" />
                                        <label for="senstive-info">Broadcast contains sensitive information</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group label-cstm">
                                  
                                    <div class="styled-input-single">
                                        <input type="checkbox" name="fieldset-2" id="embed" />
                                        <label for="embed">Embed into website</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        
                    <ul>
                        <li>

                            <div class="image-upload-btn preview-image" style="position:relative;">
                                <div class="col-sm-6">
                                    <a class='btn-purple upload-custom-image active-this-btn' href='javascript:;'>
                                        Upload Image   
                                        <input type="file" name="image" id="bd_image"  value="Image" accept="image/x-png,image/gif,image/jpeg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40"  onchange='document.getElementById("upload-image").src = window.URL.createObjectURL(this.files[0])' />

                                        {{-- <input type="file" name="image" id="bd_image" value="Image" accept="image/x-png,image/gif,image/jpeg" style='' size="40" /> --}}
                                    </a>
                                    <a href="#" class="btn btn-danger reset-image-btn" style="display: none;">Reset Image</a>
                                </div>
                                <div class="col-sm-6">
                                    <div class="uploaded-container">
                                        <img id="upload-image" src="{{ asset('images/default001.jpg') }}" />
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="broadcast-btn-style"><input id="start-streamings" class="btn btn-primary" type="button" value="Start"></li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="stream-conatiner" id="stream-stop-conatiner-wrp" style="visibility: hidden">
            <div class="live-broadcast-form">
                <form>
                    <div class="live-broadcats-strip">
                        <p id="vid-title">It is weekend here.</p>
                    </div>
                    <div class="video-frame">
                        <div id="flashContent">  
                            <!--<div id="insert_embed_here"></div>-->
                            <object type="application/x-shockwave-flash" data="{{ asset('assets/VideoIO-3.3/VideoIO.swf') }}" id="live-broadcast" width="320" height="240">
                                <param name="movie" value="{{ asset('assets/VideoIO-3.3/VideoIO.swf') }}" />
                                <param name="quality" value="high" />
                                <param name="bgcolor" value="#000000" />
                                <param name="allowFullScreen" value="true" />
                                <param name="allowScriptAccess" value="" />
                            <param name="flashVars" value="controls=true&live=true&url=rtmp://52.18.33.132:1935/live/{{ $time. '.stream' }}&publish=file1&record=true" />
                            </object>
                        </div>
                    </div>
                    <div class="live-broadcats-strip margin-bottom">
                        <div class="stream-status">
                            <div class="status-circle"><img src="{{asset('assets')}}/images/video-status-icon.png"></div>
                            <div class="text">You are live now!</div>
                        </div>
                        <!-- <div class="video-lenght">0:01:22</div> -->
                    </div>
                    <ul class="share-with-icons-live" style="display: none;">
                        <li class="twitter-icon"><a href="#" target="_blank" class="twitter"><i class="fa fa-twitter"></i></a></li>
                        <li class="facebook-icon"><a href="" target="_blank" ><i class="fa fa-facebook"></i></a></li>
                    </ul>
                    <br />
                    <input id="stop-streaming" class="stop-streaming" type="button" name="" value="STOP BROADCAST" >
                </form>
            </div>
        </div>
    </div>
</div>

   <!--including footer file-->
   <div class="clearfix"></div>


@endsection

@push('script')
<script type="text/javascript">
    
    $(document).ready(function (){
        var bd_image;
        var bid ;
        var server;
        var token = '3ef815416f775098fe977004015c6193';

        $("#bd_image").on('change', function() {
            var file = $("#bd_image").get(0).files[0];
            var reader = new FileReader();
            reader.onloadend = function() {
                console.log('RESULT', reader.result);
                bd_image = reader.result;
                // $('.live-streaming').css("background-image", "url('" + bd_image + "')");

                if($('.upload-custom-image').hasClass('active-this-btn')){
                    $('.upload-custom-image').hide().removeClass('.active-this-btn');
                    $('.reset-image-btn').show().addClass('active-this-btn');
                }
            }
            reader.readAsDataURL(file);
        });

        $('.reset-image-btn').on('click', function(event) {
            event.preventDefault();
            if($(this).hasClass('active-this-btn')){
                $(this).hide().removeClass('.active-this-btn');
                $('.upload-custom-image').show().addClass('active-this-btn');
            }
            $('.live-streaming').css("background-image", "");
        });

        $("#start-streamings").click(function(){
            var t = $("#title").val().trim();
            if(t) {
                $(this).parents().find('#stream-conatiner-wrp').fadeOut('fast');
                $(this).parents().find('#stream-stop-conatiner-wrp').fadeIn('fast');
                $('#stream-stop-conatiner-wrp').css("visibility", 'visible');
                var time = '{{ $time }}.stream';
                $('#vid-title').html(t);
                var sens = 'no';
                if($('#senstive-info').is(':checked'))
                    sens = 'yes';
                var flag = 'false';
                if($('#embed').is(':checked'))
                    flag = 'true';
                setInterval(function(){
                    $(".status-circle").fadeTo(100, 0.1).fadeTo(200, 1.0)
                }, 1000);
                $.ajax({
                    url: "{{route('startwebbroadcast')}}",
                    // contentType: 'multipart/form-data',
                    // processData: false,
                    dataType: 'json',
                    type: 'POST',

                     headers: {
                               'access-control-allow-origin': '*',
                            },
                    /*contentType:false,
                    processData:false,*/
                    data: {
                        _token: "{{ csrf_token() }}",
                        title: t,
                        geo_location: '0,0',
                        allow_user_messages: 'no',
                        is_sensitive: sens,
                        stream_url: time,
                        token: token,
                        post_plugin:flag,
                        server_input: '{{$server}}',
                        broadcast_image: bd_image,
                        user_id:'{{ auth::user()->id }}',
                    },
                    success: function (data) {
                        console.log(data);

                        bid = data.broadcast_id;

                        

                    }
                });
            }
            else
            {
                if($('.ajs-error').length > 0){
                    $('.ajs-error').css('display', 'none');
                }
                alertify.error("Please Enter Some Title");
            }

        });
        $("#stop-streaming").click(function(){
            $.ajax({
                url: "{{route('offline_broadcast')}}",
                dataType: 'json',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    token: token,
                    broadcast_id: bid
                },
                success: function () {
                    window.location.href = '{{route('user.dashboard')}}';
                    // document['externalInterface'].stopRec();
                }
            });
        });

        function show_share_icons(share_url){
            $('.share-with-icons-live').show();
            $('.twitter-icon a').attr('href', 'https://twitter.com/home?status='+share_url);
            $('.facebook-icon a').attr('href', 'https://www.facebook.com/sharer/sharer.php?u='+share_url);
        }
    });
</script>

@endpush