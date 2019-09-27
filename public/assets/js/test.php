<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Hapity</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

      <link rel="stylesheet" type="text/css" href="https://www.hapity.com/assets/css/style.css"/>
  <link rel="stylesheet" type="text/css" href="https://www.hapity.com/assets/css/tooltipster.css"/>
  <link rel="stylesheet" type="text/css" href="https://www.hapity.com/assets/css/responsive.css"/>
  <link rel="stylesheet" type="text/css" href="https://www.hapity.com/assets/css/bootstrap.min.css"/>
  <script type="text/javascript" src="https://www.hapity.com/assets/js/fb.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.hapity.com/assets/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://www.hapity.com/assets/css/alertify/alertify.rtl.css">
  <link rel="stylesheet" href="https://www.hapity.com/assets/css/alertify/themes/default.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <script src="https://www.hapity.com/assets/js/alertify.js"></script>
    <script type="text/javascript" src="https://www.hapity.com/assets/js/jquery-ias.min.js"></script>
  <script type="text/javascript" src="https://www.hapity.com/assets/js/functions.js"></script>

   <script src="https://www.hapity.com/assets/js/jquery.tooltipster.js"></script>




<!--[if IE]>
	<link href="css/ie.css" type="text/css" rel="stylesheet" media="all">
		<script type="text/javascript" src="js/ie.js"></script>
<![endif]-->

<script type="text/javascript">
    // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
      FB.api('/me', {fields: 'id,email,name,picture'}, function (response) {

          $.ajax({
              url: "https://www.hapity.com/home/fbloginUser",
              type: 'POST',
              data: {
                  social_id: response.id,
                  name: response.name,
                  email: response.email,
                  profile_picture: response.picture.data.url
              },
              success: function (msg) {
                  if (msg == 'found') {
                      window.location = 'https://www.hapity.com/main';
                  }
                  else if (msg == 'not found') {
                      window.location = 'https://www.hapity.com/register/facebook';
                  }

              }
          });
      }); //fb-api-end
  }
    </script>
        <script src='https://www.hapity.com/assets/js/jwplayer.js'></script>
            <script type='text/javascript'>jwplayer.key='fyA++R3ayz2ubL4Ae9YeON9gCFRk3VUZo+tDubFgov8=';</script>    </head>


<div class="profile-header">
    <div class="profile-logo"><a href="https://www.hapity.com/home"><img src="https://www.hapity.com/assets/images/logo-new.png"></a></div>

    

    <nav class=" HeaderNewNevigaion navbar navigation">
        <div class="navbar-header">

            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#HeaderNevigation" aria-expanded="false">
                <span class="MenuText">MENU</span>
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="HeaderNevigation">
            <ul class="nav navbar-nav">
                                <li><a href="https://www.hapity.com/main" class="sign_in">Home</a></li>
                <li><a href="https://www.hapity.com/main/settings" class="sign_in">settings</a></li>
                <li><a href="https://www.hapity.com/help" class="sign_in">Help</a></li>
                <li><a href="https://www.hapity.com/about" class="sign_in">About Us</a></li>
                <li><a href="https://www.hapity.com/home/log_out" class="sign_in">logout</a></li>
            </ul>
        </div>
    </nav>

</div><div class="profile-page new_design">
    <div class="live-streaming">
        <div class="stream-conatiner" id="stream-conatiner-wrp">
            <div class="flash-error col-xs-12 col-sm-12 col-md-12" style="display: none;">Flash player is not supported by your browser, you need flash installed to see Broadcast Videos</div>
            <div class="streamimg-logo"><img src="https://www.hapity.com/assets/images/img-streaming.png"></div>
            <div class="live-broadcast-form">
                <form>
                    <ul>
                        <li><input type="text" name="" placeholder="ENTER BROADCAST TITLE" id="title" required autocomplete="off"></li>
                        <li>
                            <input type="checkbox" name="" id="senstive-info"><label for="senstive-info">&nbsp;&nbsp;Broadcast contains sensitive information</label>
                        </li>
                        <li>
                            <input type="checkbox" name="" id="embed">
                            <label for="embed">&nbsp;&nbsp;Embed into website</label>
                        </li>
                        <li>
                            <div class="image-upload-btn" style="position:relative;">
                                <a class='btn btn-primary upload-custom-image active-this-btn' href='javascript:;'>
                                    Upload Custom Image
                                    <!-- <input type="file" name="image" id="bd_image" value="Image" accept="image/x-png,image/gif,image/jpeg" style='' size="40"  onchange='document.getElementById("upload-image").src = window.URL.createObjectURL(this.files[0])' /> -->
                                    <input type="file" name="image" id="bd_image" value="Image" accept="image/x-png,image/gif,image/jpeg" style='' size="40" />
                                </a>
                                <a href="#" class="btn btn-danger reset-image-btn" style="display: none;">Reset Image</a>
                                <!-- <div class="uploaded-container col-md-6" style="display:none; margin-top: 0;">
                                    <img id="upload-image" src="" />
                                </div> -->
                            </div>
                        </li>
                        <li><input id="start-streaming" class="start-streaming" type="button" value="start"></li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="stream-conatiner" id="stream-stop-conatiner-wrp">
            <div class="live-broadcast-form">
                <form>
                    <div class="live-broadcats-strip">
                        <p id="vid-title">It is weekend here.</p>
                    </div>
                    <div class="video-frame">
<!--                        <img src="--><!--/images/video-thumb.jpg">-->
                    <div id="flashContent">
<!--                                <img src="--><!--/images/video-status-icon.png">-->
                        <object type="application/x-shockwave-flash" width="500" height="330" id="externalInterface" data="https://www.hapity.com/assets/js/web-back-alt.swf" name="externalInterface">
                            <param name="movie" value="https://www.hapity.com/assets/js/web-back-alt.swf" />
                            <param name="quality" value="high" />
                            <param name="bgcolor" value="#ffffff" />
                            <param name="play" value="true" />
                            <param name="loop" value="true" />
                            <param name="wmode" value="window" />
                            <param name="scale" value="showall" />
                            <param name="menu" value="true" />
                            <param name="devicefont" value="false" />
                            <param name="salign" value="" />
                            <param name="allowScriptAccess" value="sameDomain" />
                            <param name="allowFullScreen" value="true" />
                        </object>
                    </div>
                    </div>
                    <div class="live-broadcats-strip margin-bottom">
                        <div class="stream-status">
                            <div class="status-circle"><img src="https://www.hapity.com/assets/images/video-status-icon.png"></div>
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
<script type="text/javascript">
    
    $(document).ready(function (){
    	var baseurl = 'https://www.hapity.com/';
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


        var time = $.now()+'.stream';
        var flash = document.getElementById("externalInterface");
        console.log(flash);
        //document['externalInterface'].title(time);
        //document['externalInterface'].record();

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
                        user_id:'183',
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
                    //window.location.href = 'https://www.hapity.com/main';
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
<script type="text/javascript" src="https://www.hapity.com/assets/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="https://www.hapity.com/assets/js/jquery.main.js"></script>
<script src="https://www.hapity.com/assets/js/cropbox.js"></script>
<script src="//js.pusher.com/2.2/pusher.min.js"></script>
<script src="https://www.hapity.com/assets/js/jquery.loader.js"></script>
<script type="text/javascript" src="https://www.hapity.com/assets/js/app.js"></script>
</body>

</html>