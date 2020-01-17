@extends('layouts.app')
@section('content')
    <div class="container">          
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                <h1 class="broadcast-heading">Broadcast for Web</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 text-center">    
                <div class="panel panel-default panel-success" style="border-color: #97be0d;">                    
                    <div class="panel-body">                        
                        <div class="row" id="live-stream-container">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <video id="localVideo" autoplay="autoplay" muted="muted" controls="controls" playsinline=""></video>
                                </div>  
                            </div>
                        </div>
                        <div class="row" id="recorded-stream-container">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe width="720" height="480" id="recorded-video" src="{{ '//stg-media.hapity.com:5443/WebRTCApp/play.html?name=' . pathinfo($broadcast->filename,  PATHINFO_FILENAME) }}"></iframe>
                                </div>  
                            </div>
                        </div>                        
                    </div>                      
                </div>                 
            </div>
        </div>
       
                
        <div class="row" id="form_container">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                <form id="broadcast_form" enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" value="update_broadcast" id="perform_action" name="perform_action" />
                    <input type="hidden" value="uploaded" id="update_as" name="update_as" />
                    <input type="hidden" value="{{$broadcast->id}}" id="broadcast_id" name="broadcast_id" />
                    <input type="hidden" value="{{ old('stream_name', pathinfo($broadcast->filename,  PATHINFO_FILENAME))}}" id="stream_name" name="stream_name" />
                    <input type="hidden" value="{{ old('broadcast_image_name', $broadcast->broadcast_image) }}" id="broadcast_image_name" name="broadcast_image_name"  />
                    <input type="hidden" value="{{ old('broadcast_video_name', $broadcast->video_name) }}" id="broadcast_video_name" name="broadcast_video_name"  />
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <input data-rule-required="true" class="form-control" type="text" value="{{ old('broadcast_title', $broadcast->title) }}" id="broadcast_title" name="broadcast_title" placeholder="Title" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <textarea class="form-control" id="broadcast_description" name="broadcast_description" placeholder="Description">{{ old('broadcast_description', $broadcast->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="broadcast_image">Upload Image</label>
                                <input class="filepond-input" type="file" value="" id="broadcast_image" name="broadcast_image" placeholder="Please select broadcast image" />
                                <div id="image_upload_loader" class="text-center text-success">
                                    <span class="label label-success"><i class="fa fa-cog fa-spin"></i> Processing Image</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="broadcast_video">Upload Video</label>
                                <input class="filepond-input" type="file" value="" id="broadcast_video" name="broadcast_video" placeholder="Please select broadcast video" />
                                <div id="video_upload_loader" class="text-center text-success">
                                    <span class="label label-success"><i class="fa fa-cog fa-spin"></i> Processing Video</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
               
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                <button type="button" onclick="" class="btn btn-lg btn-success" id="update_button">Update</button>
                <button type="button" onclick="" class="btn btn-lg btn-success" id="record_new_button">Record New</button>
                <button type="button" onclick="startPublishing()" class="btn btn-lg btn-success" id="start_publish_button">Start Publishing</button>
                <button type="button" onclick="stopPublishing()" class="btn btn-lg btn-success" id="stop_publish_button" disabled="disabled">Stop Publishing</button>
                <button type="button" onclick="" class="btn btn-lg btn-default" id="cancel_button">Cancel</button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                <span class="label label-success" id="broadcastingInfo" style="font-size: 14px; display: none;">Publishing</span>
            </div>
        </div>
            
       
        <br />
        <br />
        <br />
    </div>
@endsection

@push('css')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    
    <link href="{{ asset('assets/smart-wizard/css/smart_wizard.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/smart-wizard/css/smart_wizard_theme_circles.min.css') }}" rel="stylesheet">


@endpush

@push('script')
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/additional-methods.min.js"></script>

    <script src="{{ asset('assets/webrtc/adapter-latest.js') }}"></script>
    <script src="{{ asset('assets/webrtc/webrtc_adaptor.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#live-stream-container').hide();
            $('#start_publish_button').hide();
            $('#stop_publish_button').hide();
            $('#cancel_button').hide();
            $('#image_upload_loader').hide();
            $('#video_upload_loader').hide();

            $('body').on('click', '#record_new_button', function(){
                $(this).hide();
                $('#live-stream-container').show();
                $('#recorded-stream-container').hide();

                $('#start_publish_button').show();
                $('#stop_publish_button').show();
                $('#cancel_button').show();

                $('#update_as').val('recorded');
            });

            $('body').on('click', '#cancel_button', function(){
                $(this).hide();
                $('#live-stream-container').hide();
                $('#recorded-stream-container').show();

                $('#start_publish_button').hide();
                $('#stop_publish_button').hide();
                $('#record_new_button').show();

                $('#update_as').val('uploaded');
            });

            $('body').on('click', '#update_button', function(){
                if($('#broadcast_form').valid()) {
                    $('.broadcast-overlay').hide();

                    var form_data = $('#broadcast_form').serialize();

                    var my_request;
                    my_request = $.ajax({
                        url: "{{ route('broadcasts.ajax') }}",
                        method: 'POST',
                        data: form_data
                    });

                    my_request.done(function(response) {
                        if(response == 'success'){
                            window.location = "{{ route('broadcasts.index') }}";                               
                        }
                    });

                    my_request.error(function(){
                        alertify.error('Something went wrong!');
                    });

                    my_request.always(function(){

                    });
                
                }
            });


            FilePond.registerPlugin(FilePondPluginImagePreview);
            FilePond.registerPlugin(FilePondPluginImageCrop);
            FilePond.registerPlugin(FilePondPluginImageEdit);
            FilePond.registerPlugin(FilePondPluginImageExifOrientation);
            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginFileValidateType);

         

            const image_uploader = FilePond.create(document.querySelector('#broadcast_image'), {
                allowFileSizeValidation: true,
                maxFileSize: '50MB',
                acceptedFileTypes: ['image/*'],
                allowImageExifOrientation: true,
                allowImageCrop: true,
                server: {
                    
                    process: {
                        url: "{{ route('broadcasts.upload_image') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        withCredentials: false,  
                        onload: (response) => {
                            $('#image_upload_loader').hide();
                            $('#broadcast_image_name').val(response);
                        },
                        onerror: (response) => {
                            $('#video_upload_loader').hide();
                            return response.data;
                        },
                        ondata: (formData) => {
                            $('#image_upload_loader').show();
                            return formData;
                        }
                    }
                    
                }
            });

            const video_uploader = FilePond.create(document.querySelector('#broadcast_video'), {
                allowFileSizeValidation: true,
                maxFileSize: '100MB',
                acceptedFileTypes: ['video/*'],
                allowImageExifOrientation: true,
                allowImageCrop: true,
                server: {
                    
                    process: {
                        url: "{{ route('broadcasts.upload_video') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        withCredentials: false,  
                        onload: (response) => {
                            $('#video_upload_loader').hide();
                            $('#broadcast_video_name').val(response);
                        },
                        onerror: (response) => {
                            $('#video_upload_loader').hide();
                            return response.data;
                        },
                        ondata: (formData) => {
                            $('#video_upload_loader').show();
                            return formData;
                        }
                    }
                    
                }
            });

        });
        
        
     
        


        var token = "null";
    
        var start_publish_button = document.getElementById("start_publish_button");
        var stop_publish_button = document.getElementById("stop_publish_button");
        
        var screen_share_checkbox = document.getElementById("screen_share_checkbox");
        var install_extension_link = document.getElementById("install_chrome_extension_link");
    
        var streamNameBox = document.getElementById("stream_name");
        
        var streamId;
        
        function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;
    
            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
    
                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };
        
                
    
        function startPublishing() {
            var ts = Math.round((new Date()).getTime() / 1000);
            var name = 'stream_' + ts;
            $('#stream_name').val(name);
            webRTCAdaptor.publish(name, token);

            /*
            $('#broadcast_form').validate();

            if($('#broadcast_form').valid()) {
                $('.broadcast-overlay').hide();

                var form_data = $('#broadcast_form').serialize();

                var my_request;
                my_request = $.ajax({
                    url: "{{ route('broadcasts.ajax') }}",
                    method: 'POST',
                    data: form_data
                });

                my_request.done(function(response) {
                    if(response == 'success'){
                        $('#form_container').hide();
                        webRTCAdaptor.publish(name, token);    
                    }
                });

                my_request.error(function(){
                    alertify.error('Something went wrong!');
                });

                my_request.always(function(){

                });
                
            }
            */
        }
    
        function stopPublishing() {

            $('.broadcast-overlay').show();
            webRTCAdaptor.stop($('#stream_name').val());
        }
        
        function enableDesktopCapture(enable) {
            if (enable == true) {
                webRTCAdaptor.switchDesktopCapture($('#stream_name').val());
            }
            else {
                webRTCAdaptor.switchVideoCapture($('#stream_name').val());
            }
        }
        
        function startAnimation() {
    
            $("#broadcastingInfo").fadeIn(800, function () {
              $("#broadcastingInfo").fadeOut(800, function () {
                var state = webRTCAdaptor.signallingState($('#stream_name').val());
                if (state != null && state != "closed") {
                    var iceState = webRTCAdaptor.iceConnectionState($('#stream_name').val());
                    if (iceState != null && iceState != "failed" && iceState != "disconnected") {
                          startAnimation();
                    }
                }
              });
            });
    
          }
    
        var pc_config = null;
    
        var sdpConstraints = {
            OfferToReceiveAudio : false,
            OfferToReceiveVideo : false
    
        };
        
        var mediaConstraints = {
            video : true,
            audio : true
        };

        var host = 'stg-media.hapity.com';
        var port = '5443';
        var appName = 'WebRTCApp/';
    
        //var appName = location.pathname.substring(0, location.pathname.lastIndexOf("/")+1);
        //var path =  location.hostname + ":" + location.port + appName + "websocket";
        var path =  `${host}:${port}/WebRTCApp/websocket`;
        var websocketURL =  "ws://" + path;
        
        if (location.protocol.startsWith("https")) {
            websocketURL = "wss://" + path;
        }
        
        
        var webRTCAdaptor = new WebRTCAdaptor({
            websocket_url : websocketURL,
            mediaConstraints : mediaConstraints,
            peerconnection_config : pc_config,
            sdp_constraints : sdpConstraints,
            localVideoId : "localVideo",
            debug:true,
            callback : function(info, obj) {
                if (info == "initialized") {
                    console.log("initialized");
                    start_publish_button.disabled = false;
                    stop_publish_button.disabled = true;
                } else if (info == "publish_started") {
                    //stream is being published
                    console.log("publish started");
                    start_publish_button.disabled = true;
                    stop_publish_button.disabled = false;
                    startAnimation();
                } else if (info == "publish_finished") {
                    //stream is being finished
                    console.log("publish finished");
                    start_publish_button.disabled = false;
                    stop_publish_button.disabled = true;
                }
                else if (info == "screen_share_extension_available") {
                    screen_share_checkbox.disabled = false;
                    console.log("screen share extension available");
                    install_extension_link.style.display = "none";
                }
                else if (info == "screen_share_stopped") {
                    console.log("screen share stopped");
                }
                else if (info == "closed") {
                    //console.log("Connection closed");
                    if (typeof obj != "undefined") {
                        console.log("Connecton closed: " + JSON.stringify(obj));
                    }
                }
                else if (info == "pong") {
                    //ping/pong message are sent to and received from server to make the connection alive all the time
                    //It's especially useful when load balancer or firewalls close the websocket connection due to inactivity
                }
                else if (info == "refreshConnection") {
                    startPublishing();
                }
                else if (info == "updated_stats") {
                    //obj is the PeerStats which has fields
                     //averageOutgoingBitrate - kbits/sec
                    //currentOutgoingBitrate - kbits/sec
                    console.log("Average outgoing bitrate " + obj.averageOutgoingBitrate + " kbits/sec"
                            + " Current outgoing bitrate: " + obj.currentOutgoingBitrate + " kbits/sec");
                     
                }
            },
            callbackError : function(error, message) {
                //some of the possible errors, NotFoundError, SecurityError,PermissionDeniedError
                
                /*
                console.log("error callback: " +  JSON.stringify(error));
                var errorMessage = JSON.stringify(error);
                if (typeof message != "undefined") {
                    errorMessage = message;
                }
                var errorMessage = JSON.stringify(error);
                if (error.indexOf("NotFoundError") != -1) {
                    errorMessage = "Camera or Mic are not found or not allowed in your device";
                }
                else if (error.indexOf("NotReadableError") != -1 || error.indexOf("TrackStartError") != -1) {
                    errorMessage = "Camera or Mic is being used by some other process that does not let read the devices";
                }
                else if(error.indexOf("OverconstrainedError") != -1 || error.indexOf("ConstraintNotSatisfiedError") != -1) {
                    errorMessage = "There is no device found that fits your video and audio constraints. You may change video and audio constraints"
                }
                else if (error.indexOf("NotAllowedError") != -1 || error.indexOf("PermissionDeniedError") != -1) {
                    errorMessage = "You are not allowed to access camera and mic.";
                }
                else if (error.indexOf("TypeError") != -1) {
                    errorMessage = "Video/Audio is required";
                }
                */
            
                //alertify.error(errorMessage);
                console.log(error, message);
                
            }
        });
    </script>
@endpush