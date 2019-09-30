@php 

@endphp

@extends('layouts.app')

@push('css')
 
@endpush
@section('content')

<div class="profile-page new_design webcast-page-wrapper">
    <div class="live-streaming">
        <div class="stream-conatiner" id="stream-conatiner-wrp">
            <h3 class="braodcast-web-heading">Broadcast for web</h3>
            <div class="flash-error webcast-page-flash-error col-xs-12 col-sm-12 col-md-12" style="display: none;">Flash player is not supported by your browser, you need flash installed to see Broadcast Videos</div>
            <div class="streamimg-logo"><img src="{{asset('assets')}}/images/broadcast-icon-new.png" width="100"></div>
            <div class="live-broadcast-form">

                <form>
                        <div class="form-group clearfix">
                            <input type="text" class="broadcast-title" name="" placeholder="ENTER BROADCAST TITLE" id="title" required autocomplete="off">
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group label-cstm">
                                   <!--  <input type="checkbox" name="" id="senstive-info">
                                    <label for="senstive-info">&nbsp;&nbsp;Broadcast contains sensitive information</label>
 -->
                                    <div class="styled-input-single">
                                        <input type="checkbox" name="fieldset-1" id="senstive-info" />
                                        <label for="senstive-info">Broadcast contains sensitive information</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group label-cstm">
                                   <!--  <input type="checkbox" name="" id="embed">
                                    <label for="embed">&nbsp;&nbsp;Embed into website</label> -->

                                    <div class="styled-input-single">
                                        <input type="checkbox" name="fieldset-2" id="embed" />
                                        <label for="embed">Embed into website</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        
                    <ul>
                        <li>
                            <div class="image-upload-btn" style="position:relative;">
                                <a class='btn-purple upload-custom-image active-this-btn' href='javascript:;'>
                                    Upload Image
                                   
                                    <input type="file" name="image" id="bd_image" value="Image" accept="image/x-png,image/gif,image/jpeg" style='' size="40" />
                                </a>
                                <a href="#" class="btn btn-danger reset-image-btn" style="display: none;">Reset Image</a>
                                
                            </div>
                        </li>
                        <li class="broadcast-btn-style"><input id="start-streaming" class="btn btn-primary" type="button" value="Start"></li>
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
                        <object type="application/x-shockwave-flash" width="100%" height="330" id="externalInterface" data="{{asset('assets/js')}}/web-back.swf" name="externalInterface">
                            <param name="movie" value="{{asset('assets/js')}}/web-back.swf" />
                            <param name="quality" value="high" />
                            <param name="bgcolor" value="#ffffff" />
                            <param name="play" value="true" />
                            <param name="loop" value="true" />
                            <param name="wmode" value="window" />
                            <param name="scale" value="showall" />
                            <param name="menu" value="true" />
                            <param name="devicefont" value="false" />
                            <param name="salign" value="" />
                            <param name="allowScriptAccess" value="always" />
                            <param name="allowFullScreen" value="true" />
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

        jQuery("#bd_image").on('change', function() {
            var file = jQuery("#bd_image").get(0).files[0];
            var reader = new FileReader();
            reader.onloadend = function() {
                console.log('RESULT', reader.result);
                bd_image = reader.result;
                jQuery('.live-streaming').css("background-image", "url('" + bd_image + "')");

                if(jQuery('.upload-custom-image').hasClass('active-this-btn')){
                    jQuery('.upload-custom-image').hide().removeClass('.active-this-btn');
                    jQuery('.reset-image-btn').show().addClass('active-this-btn');
                }
            }
            reader.readAsDataURL(file);
        });

        jQuery('.reset-image-btn').on('click', function(event) {
            event.preventDefault();
            if(jQuery(this).hasClass('active-this-btn')){
                jQuery(this).hide().removeClass('.active-this-btn');
                jQuery('.upload-custom-image').show().addClass('active-this-btn');
            }
            jQuery('.live-streaming').css("background-image", "");
        });

        $("#start-streaming").click(function(){
            var t = $("#title").val().trim();
            if(t) {
                $(this).parents().find('#stream-conatiner-wrp').fadeOut('fast');
                $(this).parents().find('#stream-stop-conatiner-wrp').fadeIn('fast');
                $('#stream-stop-conatiner-wrp').css("visibility", 'visible');
                var time = $.now()+'.stream';
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
                    url: baseurl+'main/startwebbroadcast/',
                    dataType: 'json',
                    type: 'POST',
                    /*contentType:false,
                    processData:false,*/
                    data: {
                        title: t,
                        geo_location: '0,0',
                        allow_user_messages: 'no',
                        is_sensitive: sens,
                        stream_url: time,
                        token: token,
                        post_plugin:flag,
                        server: '52.18.33.132',
                        broadcast_image: bd_image,
                        user_id:'{{ auth::user()->id }}',
                    },
                    success: function (data) {
                        bid = data.broadcast_id;
                        
                        /*setTimeout(function(){
                            show_share_icons(data.share_url);
                        }, 3000);*/
                        //server = 'rtmp://'+data.server+':1935/live?wozpubuser:Pu8Eg3n3_';
                        //document['externalInterface'].changeServer(server);
                        //setTimeout(function(){
                            document['externalInterface'].title(time);
                        //},2000);
                        //console.log(server);
                        setTimeout(function(){
                            document['externalInterface'].record();
                        },3000);
                        setInterval(function(){
                            $.ajax({
                                url: baseurl+'main/update_timestamp_broadcast/',
                                dataType: 'jsonp',
                                type: 'GET',
                                data: {
                                    token: token,
                                    broadcast_id: bid
                                },
                                success: function (data) {

                                }
                            });
                        }, 30000);

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
                url: baseurl+'main/offline_broadcast/',
                dataType: 'json',
                type: 'GET',
                data: {
                    token: token,
                    broadcast_id: bid
                },
                success: function () {
                    window.location.href = '{{url('home/')}}';
                    //document['externalInterface'].stopRec();
                }
            });
        });

        function show_share_icons(share_url){
            jQuery('.share-with-icons-live').show();
            jQuery('.twitter-icon a').attr('href', 'https://twitter.com/home?status='+share_url);
            jQuery('.facebook-icon a').attr('href', 'https://www.facebook.com/sharer/sharer.php?u='+share_url);
        }
    });
</script>

@endpush