@extends('layouts.app')
@push('css')
<link href="{{ asset('assets/css') }}/jquery.loader.css" rel="stylesheet" />

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



@php
    if($broadcast_data['broadcast_image']){
        $image = $broadcast_data['broadcast_image'];
    } else {
        $image = 'https://www.hapity.com/assets/images/picture.png';
    }
@endphp
<div class="profile-page new_design">
    <div class="create-content-wrap">
        <div class="create-content-conatiner" id="create-content-conatiner-wrp">
            <div class="create-content-form">
            <form method="post" data-type="edit" action="{{route('edit_content_submission')}}" enctype="multipart/form-data">
                @csrf
                    <ul>
                        <li><input type="text" name="title" placeholder="Title" value="<?php echo $broadcast_data['title']; ?>" id="title" required autocomplete="off"></li>
                        <li><textarea name="description" id="description" placeholder="Description"><?php echo $broadcast_data['description']; ?></textarea></li>
                    </ul>   
                    <div class="upload-actions">
                        <h3>Upload a Video or Picture</h3>
                        <div class="row">
                            <div class="upload-containe col-xs-4 col-md-4 video-upload-btn" style="position:relative;">
                             
                                <a class='btn btn-primary' href='javascript:;'>
                                Update video
                                <input type="file" name="video" value="Video" accept="video/mp4,video/x-m4v,video/*" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40" id="video-upload-btn" onchange=''/>
                                </a>
                                <div class="uploaded-container left-uploaded-conteiner">
                                    <video id="upload-video" style="display:none;" autoplay muted>
                                        <source src="" type="video/mp4">
                                    </video>
                                    <img id="upload-video-placeholder" src="https://www.hapity.com/assets/images/video.png" />
                                </div>
                            </div>
                            <div class="col-xs-4 col-md-4">
                            <span class="or-text separator">OR</span>
                            </div>
                            <div class="upload-containe col-xs-4 col-md-4 image-upload-btn" style="position:relative;">
                            <a class='btn btn-primary' href='javascript:;'>
                                Update image
                              
                                <input type="file" name="image" value="Image" accept="image/x-png,image/gif,image/jpeg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40"  onchange='document.getElementById("upload-image").src = window.URL.createObjectURL(this.files[0])' />
                               
                                </a>
                                  <div class="uploaded-container">
                                	<img id="upload-image" src="{{ $image }}" />
                                  </div>
                            </div>
                        </div>    
                    </div>
                    <br />
                    <input id="bid" type="hidden" value="{{ $broadcast_data['id'] }}" name="bid" />
                    <input id="token" type="hidden" value="{{ Auth::user()->profile->auth_key }}" name="token" />
                    <input id="user_id" type="hidden" value="{{ Auth::user()->id }}" name="user_id" />
                    <input id="create-content-btn" class="create-content-btn" type="submit" value="Update Broadcast">
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