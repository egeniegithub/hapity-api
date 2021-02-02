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
</style>
  @endpush
@section('content')
	<section class="help-new">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="main-heading text-center">
            <h1 class="page_title text-center pur-col font-bold">How to integrate into your website</h1>
            <h4><strong>Please Note</strong> Hapity is not suitable for CCTV or Adult Services. Our fair use policy permits us to cut off and not store over long livestreams.</h4>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Integrate with Wordpress - Starts Here  -->
        <div class="col-md-6 video-lg">
          <div class="video-heading text-center">
            <h4>Integrate with Wordpress</h4>
          </div>
           <div class="slide-vector embed-responsive embed-responsive-16by9"> <!-- <img src="{{asset('/')}}home/images/play-video.png" alt="Video Frame"> -->
                <video id="video1" controls poster="{{asset('assets')}}/images/integrate-with-wordpress.jpg"
             src="{{asset('assets')}}/videos/How-To-WordPress_Edit03-Vimeo_720p.mov"></video>
                </div>
          <div class="download-buttons-stuff">
            <div class="row no-margin">
              <div class="col-md-6 no-padding">
                <div class="download-guide text-center">
                  <a href="{{asset('assets')}}/docs/help-docs/Hapity for WordPress.docx" target="_blank">
                    <ul>
                      <li><img src="{{asset('assets')}}/images/download-guide.png" alt="logo"></li>
                      <li><h4>Download Guide</h4></li>
                    </ul>
                  </a>
                </div>
              </div>

              <div class="col-md-6 no-padding">
                <div class="download-plugin text-center">
                  <a href="https://wordpress.org/plugins/wp-hapity/">
                    <ul>
                      <li><img src="{{asset('assets')}}/images/download-plugin.png" alt="logo"></li>
                      <li><h4>Download Plugin</h4></li>
                    </ul>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="transcript-bottom text-center">
            <a href="{{asset('assets')}}/docs/Hapity Installtion Guide-Wordpress Script.pdf" target="_blank">
              <p><h4><span>Transcript</span></h4></p>
            </a>
          </div>
        </div>
        <!-- Integrate with Wordpress - Ends Here  -->



        <!-- Integrate with Drupal - Starts Here  -->
        <div class="col-md-6 video-lg">
          <div class="video-heading text-center">
            <h4>Integrate with Drupal</h4>
          </div>
         <div class="slide-vector embed-responsive embed-responsive-16by9"> <!-- <img src="{{asset('assets/')}}home/images/play-video.png" alt="Video Frame"> -->
                    <video id="video2" controls poster="{{asset('assets')}}/images/integrate-with-drupal.jpg" src="{{asset('assets')}}/videos/How_To_Drupal_Edit03-Vimeo_720p.mov"></video>
                </div>
          <div class="download-buttons-stuff">
            <div class="row no-margin">
              <div class="col-md-6 no-padding">
                <div class="download-guide text-center">
                  <a href="{{asset('assets')}}/docs/help-docs/Hapity for Drupal.docx" target="_blank">
                    <ul>
                      <li><img src="{{asset('assets')}}/images/download-guide.png" alt="logo"></li>
                      <li><h4>Download Guide</h4></li>
                    </ul>
                  </a>
                </div>
              </div>

              <div class="col-md-6 no-padding">
                <div class="download-plugin text-center">
                  <a href="https://www.drupal.org/project/hapity" target="_blank">
                    <ul>
                      <li><img src="{{asset('assets')}}/images/download-plugin.png" alt="logo"></li>
                      <li><h4>Download Plugin</h4></li>
                    </ul>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="transcript-bottom text-center">
            <a href="{{asset('assets')}}/docs/Hapity Installtion Guide-Drupal Script.pdf" target="_blank">
              <p><h4><span>Transcript</span></h4></p>
            </a>
          </div>
        </div>
        <!-- Integrate with Drupal - Ends Here  -->



        <!-- Integrate with Joomla - Starts Here  -->
        <div class="col-md-6 video-lg">
          <div class="video-heading text-center">
            <h4>Integrate with Joomla</h4>
          </div>
          <div class="slide-vector embed-responsive embed-responsive-16by9"> <!-- <img src="{{asset('/')}}('assets/'); ?>/home/images/play-video.png" alt="Video Frame"> -->
                    <video id="video3" controls poster="{{asset('assets')}}/images/integrate-with-joomla.jpg" src="{{asset('assets')}}/videos/How-To_Joomla_Edit03_Vimeo_720p.mov"></video>
                </div>
          <div class="download-buttons-stuff">
            <div class="row no-margin">
              <div class="col-md-6 no-padding">
                <div class="download-guide text-center">
                   <a href="{{asset('assets')}}/docs/help-docs/Hapity for Joomla.docx" target="_blank">
                    <ul>
                      <li><img src="{{asset('assets')}}/images/download-guide.png" alt="logo"></li>
                      <li><h4>Download Guide</h4></li>
                    </ul>
                  </a>
                </div>
              </div>

              <div class="col-md-6 no-padding">
                <div class="download-plugin text-center">
                  <a href="{{asset('uploads')}}/hapity_joomla.zip">
                    <ul>
                      <li><img src="{{asset('assets')}}/images/download-plugin.png" alt="logo"></li>
                      <li><h4>Download Plugin</h4></li>
                    </ul>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="transcript-bottom text-center">
            <a href="{{asset('assets')}}/docs/Hapity Installtion Guide-Joomla Script.pdf" target="_blank">
              <p><h4><span>Transcript</span></h4></p>
            </a>
          </div>
        </div>
        <!-- Integrate with Joomla - Ends Here  -->



        <!-- Guide for Other Websites - Starts Here  -->
        <div class="col-md-6 video-lg">
          <div class="video-heading text-center">
            <h4>Guide for other websites</h4>
          </div>
             <img src="{{asset('assets')}}/images/guide-for-other-websites.jpg" class="img-w-100">

          <div class="download-buttons-stuff">
            <div class="row no-margin">
              <div class="col-md-12 no-padding">
                <div class="download-guide text-center">
                <a href="{{asset('assets/docs')}}/hapity-for-custom.pdf" target="_blank">
                    <ul>
                      <li><img src="{{asset('assets')}}/images/download-guide.png" alt="logo"></li>
                      <li><h4>Download Guide</h4></li>
                    </ul>
                  </a>
                </div>
              </div>

            </div>
          </div>

         <div class="transcript-bottom text-center">
            <a href="javascript::void()">
              <p><h4><span>Transcript</span></h4></p>
            </a>
          </div>
        </div>
        <!-- Guide for Other Websites - Ends Here  -->
        <!-- Guide for OBS - Starts Here  -->
        <div class="col-md-6 video-lg">
            <div class="video-heading text-center">
              <h4>Guide for OBS</h4>
            </div>
            <div class="slide-vector embed-responsive embed-responsive-16by9"> <!-- <img src="{{asset('/')}}('assets/'); ?>/home/images/play-video.png" alt="Video Frame"> -->
                <video id="video3" controls poster="{{asset('assets')}}/images/obs.png" src="{{asset('assets')}}/videos/Hapity-OBS-Tutorial-HD-1080p.mov"></video>
            </div>
           <div class="transcript-bottom text-center">
              <a href="javascript::void()">
                <p><h4><span>Transcript</span></h4></p>
              </a>
            </div>
          </div>
          <!-- Guide for OBS - Ends Here  -->
          <!-- Guide for YouTube Integration - Starts Here  -->
        <div class="col-md-6 video-lg">
            <div class="video-heading text-center">
              <h4>Guide for YouTube Integration</h4>
            </div>
            <div class="slide-vector embed-responsive embed-responsive-16by9"> <!-- <img src="{{asset('/')}}('assets/'); ?>/home/images/play-video.png" alt="Video Frame"> -->
                <video id="video3" controls poster="{{asset('assets')}}/images/youtube.png" src="{{asset('assets')}}/videos/Hapity-YT-Tutorial-HD-1080p.mov"></video>
            </div>
            <div class="transcript-bottom text-center">
              <a href="javascript::void()">
                <p><h4><span>Transcript</span></h4></p>
              </a>
            </div>
          </div>
          <!-- Guide for Other Websites - Ends Here  -->
      </div>
    </div>
    <!-- Section - How to use Hapity  -->
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="main-heading text-center">
            <h1 class="page_title text-center pur-col font-bold">How to use hapity</h1>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Guide for Mobiles - Starts Here  -->
        <div class="col-md-4 video-lg">
          <div class="video-heading text-center">
            <h4>Guide for Mobiles</h4>
          </div>
            <div class="slide-vector embed-responsive embed-responsive-16by9">
                    <video id="video4" controls poster="{{asset('assets')}}/images/guide-for-mobiles.jpg" src="{{asset('assets')}}/videos/Hapity_Mobile_Guide_Edit02-Vimeo_720p.mov"></video>
                </div>
          <div class="download-buttons-stuff">
            <div class="row no-margin">
             <div class="col-md-12 no-padding">
                <div class="download-guide text-center">
                  <a href="javascript::void()">
                    <ul>
                      <li><img src="{{asset('assets')}}/images/download-guide.png" alt="logo"></li>
                      <li><h4>Download Guide</h4></li>
                    </ul>
                  </a>
                </div>
              </div>

            </div>
          </div>

          <div class="transcript-bottom text-center">
            <a href="{{ asset('assets') }}/docs/Hapity Mobile Broadcast Guide Script.pdf">
              <p><h4><span>Transcript</span></h4></p>
            </a>
          </div>
        </div>
        <!-- Guide for Mobiles - Ends Here  -->



        <!-- Broadcasting from your PC - Starts Here  -->
        <div class="col-md-4 video-lg">
          <div class="video-heading text-center">
            <h4>Broadcasting from your PC</h4>
          </div>
            <div class="slide-vector embed-responsive embed-responsive-16by9">
                <video id="video5" controls poster="{{asset('assets')}}/images/broadcasting-from-your-pc.jpg" src="{{asset('assets')}}/videos/Hapity_PC_Guide_Edit02-Vimeo_720p.mov"></video>
            </div>
          <div class="download-buttons-stuff">
           <div class="row no-margin">
              <div class="col-md-12 no-padding">
                <div class="download-guide text-center">
                  <a href="javascript::void()">
                    <ul>
                      <li><img src="{{asset('assets')}}/images/download-guide.png" alt="logo"></li>
                      <li><h4>Download Guide</h4></li>
                    </ul>
                  </a>
                </div>
              </div>

            </div>
          </div>

          <div class="transcript-bottom text-center">
            <a href="{{ asset('assets') }}/docs/Haptity PC Broadcast Guide Script.pdf">
              <p><h4><span>Transcript</span></h4></p>
            </a>
          </div>
        </div>
        <!-- Broadcasting from your PC - Ends Here  -->



        <!-- Guide for enabling flash - Starts Here  -->
        <!-- <div class="col-md-4 video-lg">
          <div class="video-heading text-center">
            <h4>Guide for enabling flash</h4>
          </div>
           <img src="{{asset('assets')}}/images/guide-for-enabling-flash.jpg" class="img-w-100">

          <div class="download-buttons-stuff">
            <div class="row no-margin">
              <div class="col-md-12 no-padding">
                <div class="download-guide text-center">
                  <a href="{{ asset('assets') }}/docs/help-docs/Hapity – Enabling Flash player in Chrome.docx">
                    <ul>
                      <li><img src="{{asset('assets')}}/images/download-guide.png" alt="logo"></li>
                      <li><h4>Download Guide</h4></li>
                    </ul>
                  </a>
                </div>
              </div>

            </div>
          </div>

        <div class="transcript-bottom text-center">
            <a href="javascript::void()">
              <p><h4><span>Transcript</span></h4></p>
            </a>
          </div>
        </div> -->
        <!-- Guide for enabling flash - Starts Here  -->
      </div>
    </div>
</section><!-- help new ends here -->


<!-- modal -video -->
<div class="modal fade modal-dismiss" id="wp-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-close-cstm" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-content">
            <div class="modal-body video-body">
              <div class="slide-vector embed-responsive embed-responsive-16by9">
                    <video id="video1" controls poster="{{asset('assets')}}/videos/default-video.jpg" src="{{asset('assets')}}/videos/How-To-WordPress_Edit03-Vimeo_720p.mov"></video>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-dismiss" id="drupal-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-close-cstm" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-content">
            <div class="modal-body video-body">
              <div class="slide-vector embed-responsive embed-responsive-16by9">
                    <video id="video2" controls poster="{{asset('assets')}}/videos/default-video.jpg" src="{{asset('assets')}}/videos/How_To_Drupal_Edit03-Vimeo_720p.mov"></video>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-dismiss" id="joomla-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-close-cstm" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-content">
            <div class="modal-body video-body">
              <div class="slide-vector embed-responsive embed-responsive-16by9">
                    <video id="video3" controls poster="{{asset('assets')}}/videos/default-video.jpg" src="{{asset('assets')}}/videos/How-To_Joomla_Edit03_Vimeo_720p.mov"></video>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-dismiss" id="mob-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-close-cstm" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-content">
            <div class="modal-body video-body">
              <div class="slide-vector embed-responsive embed-responsive-16by9">
                    <video id="video4" controls poster="{{asset('assets')}}/videos/default-video.jpg" src="{{asset('assets')}}/videos/Hapity_Mobile_Guide_Edit02-Vimeo_720p.mov"></video>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-dismiss" id="broadcast-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-close-cstm" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-content">
            <div class="modal-body video-body">
              <div class="slide-vector embed-responsive embed-responsive-16by9">
                    <video id="video5" controls poster="{{asset('assets')}}/videos/default-video.jpg" src="{{asset('assets')}}/videos/Hapity_PC_Guide_Edit02-Vimeo_720p.mov"></video>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
@endsection

@push('script')
<script>

    $('.modal-dismiss').on('hidden.bs.modal', function () {
        reset_videos_hapity();
    });


    function reset_videos_hapity() {

        $('#video1')[0].pause();
        $('#video2')[0].pause();
        $('#video3')[0].pause();
        $('#video4')[0].pause();
        $('#video5')[0].pause();

        $('#video1')[0].currentTime = 0;
        $('#video2')[0].currentTime = 0;
        $('#video3')[0].currentTime = 0;
        $('#video4')[0].currentTime = 0;
        $('#video5')[0].currentTime = 0;

    }
</script>

@endpush
