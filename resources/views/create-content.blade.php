@extends('layouts.app')

@push('css')
	<link href="{{asset('assets/css')}}/jquery.loader.css" rel="stylesheet" />

 	<style type="text/css">
    #jquery-loader-background {
        background: #000 !important;
    }
    #upload-video {
        width: 100%;
        height: 111px;
        display: block;
    }
</style>

@endpush
@section('content')

<div class="profile-page new_design" id="create-content-main">
    <div class="create-content-wrap mrg-btm">
        <div class="create-content-conatiner" id="create-content-conatiner-wrp">
            <h3 class="braodcast-web-heading">Upload video or image</h3>
            <div class="upload-image-thumb">
                <img src="{{asset('assets')}}/images/upload-icon-new.png" width="150">
            </div>
            <div class="create-content-form">
                
                    <form method="post" action="{{route('create_content_submission')}}" enctype="multipart/form-data">
                    @csrf
                    <ul class="title-desc-section">
                        <li><input type="text" name="title" placeholder="Title" id="title" required autocomplete="off"></li>
                        <li><textarea name="description" id="description" placeholder="Description" required></textarea></li>
                    </ul><!-- title-desc-section -->   
                    <div class="upload-actions">
                       
                        <div class="row">
                            <div class="upload-containe col-xs-6 col-sm-6 col-md-4 video-upload-btn" style="position:relative;">
                                
                                <a class='btn-purple' href='javascript:;'>
                                Upload video
                                <input type="file" name="video" value="Video" required accept="video/mp4,video/x-m4v,video/*" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40" id="video-upload-btn" onchange=''/>
                                </a>
                                @if($errors->has('video')) 
                                    {{ $errors->first('video') }} 
                                @endif
                                <div class="uploaded-container left-uploaded-conteiner">
                                    <video id="upload-video" style="display:none;" autoplay muted>
                                        <source src="" type="video/mp4">
                                    </video>
                                    <img id="upload-video-placeholder" src="https://www.hapity.com/assets/images/video.png" />
                                </div>
                            </div>
                            <div class="col-xs-4 col-md-4">
                            <!-- <span class="or-text separator">OR</span> -->
                            </div>
                            <div class="upload-containe col-xs-6 col-sm-6 col-md-4 image-upload-btn" style="position:relative;">
                            <a class='btn-purple' href='javascript:;'>
                                Upload image
                                <!-- <span><i class="fa fa-image"></i> Image</span> -->
                                <input type="file" name="image" required value="Image" accept="image/x-png,image/gif,image/jpeg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40"  onchange='document.getElementById("upload-image").src = window.URL.createObjectURL(this.files[0])' />
                               
                                </a>
                                 @if($errors->has('image')) 
                                    {{ $errors->first('image') }} 
                                @endif
                                  <div class="uploaded-container">
                                	<img id="upload-image" src="https://www.hapity.com/assets/images/picture.png" />
                                  </div>
                            </div>
                        </div>    
                    </div>
                    <br />
                    <input id="token" type="hidden" value="{{ auth::user()->profile->auth_key }}" name="token" />
                    <input id="user_id" type="hidden" value="{{ auth::user()->id }}" name="user_id" />
                    <input  class="btn btn-primary btn-width" type="submit" value="Upload">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>


@endsection
@push('script')
	
<script>
    (function localFileVideoPlayer() {
    'use strict'
      var URL = window.URL || window.webkitURL
      
      var playSelectedFile = function (event) {
        var file = this.files[0]
        var type = file.type
        var videoNode = document.querySelector('video')
        var canPlay = videoNode.canPlayType(type)
        if (canPlay === '') canPlay = 'no'
        var message = 'Can play type "' + type + '": ' + canPlay
        var isError = canPlay === 'no'

        if (isError) {
          return
        }

        var fileURL = URL.createObjectURL(file)
        videoNode.src = fileURL
      }
      var inputNode = document.getElementById('video-upload-btn');
      inputNode.addEventListener('change', playSelectedFile, false)
    })()

    jQuery(document).ready(function($) {
        jQuery('#video-upload-btn').on('change', function(event) {
            event.preventDefault();
            jQuery("video").show();
            jQuery("#upload-video-placeholder").hide();
        });
    });
</script>
@endpush