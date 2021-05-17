@extends('layouts.app')
  @push('css')
    <!-- ------------Recommend Videos------------- -->
<style>
/** New help page **/
.video-heading.text-center {
    background: #391751;
    padding: 6px;
    margin-bottom: 1PX;
}
.col-md-6.video-lg .embed-responsive-16by9{padding-bottom: 57.25%!important;}
.col-md-4.video-lg .slide-vector.embed-responsive.embed-responsive-16by9{
        padding-bottom: 59.25%!important;
}
.video-heading.text-center h4{
    color: #fff;
}
.hapity-video-new {
    margin-top: -26px;
    margin-bottom: 0px;
}
.main-heading{
    margin-bottom: 20px 0px 40px 0px;
}
.main-heading.text-center {
    margin-bottom: 20px;
}
.download-guide{
    background-color: #dedede;
    padding: 15px 0px;
    margin-top: -6px;
    border-right: 1px solid #c8c8c8;
}
.download-guide:hover{
    background-color: #d3d3d3;
}
.download-guide a:hover{
    text-decoration: none;
}
.download-guide ul{
    list-style: none;
}
.download-plugin{
    background-color: #dedede;
    padding: 15px 0px;
    margin-top: -6px;
}
.download-plugin:hover{
    background-color: #d3d3d3;
}
.download-plugin a:hover{
    text-decoration: none;
}
.download-plugin ul{
    list-style: none;
}
.transcript-bottom{
    background-color: #dedede;
    width: 100%;
    padding: 10px 0px;
    border-top: 1px solid #c8c8c8;
}
.transcript-bottom span{
    font-size: 14px;
}
.transcript-bottom:hover{
    background-color: #d3d3d3;
}
.transcript-bottom a:hover{
    text-decoration: none;
}
.no-padding{
    padding: 0;
}
.no-margin{
    margin: 0;
}
.video-lg{
    margin-bottom: 30px;
}
.hapity-video-new-small {
    margin-top: 0px;
    margin-bottom: 0px;
}
.img-w-100{width: 100%;}
.download-guide a,
.download-plugin a,
.transcript-bottom a{color:#391751 !important; text-decoration: none;}
.font-bold{font-weight:bold;}
/** New help page ends here **/
    .guide-text{ margin:15px 0; }
    .purple-txt-help{ color:#391751; font-size:24px; }
    .purple-txt-help span{ display: inline; }
    .purple-txt-help a{ color:#391751; text-decoration:none; }
    .child-2-span{ position: relative;top: 10px;}
    .pur-col{ color:#391751; margin: 30px 0 40px 0 !important;}
    .help-icons-wrap{ margin-bottom:20px; }
    .video-body .jwplayer{ width:100% !important; height:300px !important; }
    .modal-close-cstm .close{position:absolute; z-index: 99;right: 5px;}
    .product-icon{position:relative;}
    .play-icon{position:absolute; top:15px;left:0; right:0; color: #fff;}
    @media (max-width:1200px){
        .product-icon a img{ width:100%}
        .purple-txt-help span img{width:30px;}
    }
    @media (max-width:1024px){
        .product-icon a img{ width:100%}
        .purple-txt-help span img{width:30px;}
        .help-icons-wrap {margin-bottom: 70px;}
        .purple-txt-help {font-size: 20px;}
    }
    .header-wrapper {
        z-index: 10;
    }
</style>
  @endpush
@section('content')
<section class="help-new">
    <img width="100%" style="margin-top:-80px" src="{{ asset('images/stream-on-youtube.png')}}"/>
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="main-heading">
                <h1 class="page_title pur-col font-bold">Stream Live on YouTube with Hapity!</h1>
                <h2>Livestreaming to YouTube Has Never Been Easier</h2>
                <p>Don’t you miss the days when everyone could start a livestream on YouTube? It was so easy. But, nooooo, nooooo, YouTube had to go and make it hard which is a real shame for the rest of us who love to livestream</p>
                <p>We have the solution and -- wait for it -- it’s free.</p>
                <p>Yes, I know you can’t believe your eyes. One of our users, Bruce McLaughlin, could not believe it either.</p>
                <p class="text-center">“So what's the catch? I just installed the Hapity plugin on my website, copied and pasted a code, and BAM! I'm up and running! I spent a week doing this with a different service and they wanted many hundreds of dollars per month. How do you guys make money? There aren't even ads on your website. Can it support a thousand people all watching at once?”</p>
                <p>There’s no catch, Bruce. We believe in the Open Source Movement (or we’re undecided on our revenue plan). But hey, why not try it out? Yes, a thousand people can watch at once. This is great for musicians* and nonprofits as well as on-the-beat reporters.</p>
                <p><a href="https://wordpress.org/plugins/wp-hapity/">Hapity is a free WordPress plugin</a> and our tutorial on how to <a href="https://blog.hapity.com/wordpress-plugin/">set up your Hapity account will help you do that bit.</a> </p>
                <h2>Here's how to broadcast live on YouTube using the Hapity app.</h2>
                <video width="100%" controls>
                    <source src="https://hapity.com/assets/videos/Hapity-YT-Tutorial-HD-1080p.mov" type="video/mp4">
                  Your browser does not support the video tag.
                  </video>
                  <p>Firstly, make sure your YouTube account is enabled for live streaming by clicking the create button on your YouTube channel and selecting go live. Be aware that's live streaming can take up to 24 hours to activate.</p>
                  <p>Log into your Hapity account and click on the create new video icon. You then need to click on the video icon, which will prompt you to allow Hapity to link with your Google account.</p>
                  <p>When you're linked to Google, the video icon will turn red. Enter a title for your broadcast and click start.</p>
                  <p>Yay! Your broadcast is now live on YouTube.</p>
                  <p>To finish your broadcast, click the stop button and end your stream.                </p>
                  <p>Your broadcast is automatically saved to your history page. How cool is that?</p>

                  <h2>What Will Your Next Livestream Be?</h2>
                  <p>The only thing we can’t do is telling you what to stream*. What topic will you cover? Will it be weekly or daily? We can’t wait to see your channel grow!</p>
                  <a href="https://wordpress.org/plugins/wp-hapity/" class="btn" style="background: rgb(151, 190, 13) none repeat scroll 0 0;color:#fff">Download Hapity</a>

                  <h2>The Disclaimer -- What Can’t You Stream?</h2>
                  <p>No CCTV, dashcam, porn, violence, or extreme politics. As with any content creation, obey copyright rules. We have fair use rules for overlong videos, however, broadcasts long enough for religious services and sports are fine.</p>
            </div>
        </div>
      </div>
    </div>
</section>
@endsection
@push('css')
    <meta name="description" content="If you’re looking for great software that allows you to livestream to YouTube, Hapity.com is just the product. Why not try this WordPress plugin today?">
    <meta name="keywords" content="Hapity, Livestream, Livestream to Youtube, video stream to YouTube, video stream, video streaming software, stream video to YouTube, wordpress plugin">
@endpush
