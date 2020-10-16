@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                <h1 class="broadcast-heading">Lets Create Something Awesome<br /><small>Upload Broadcast</small></h1>
            </div>
        </div>



        <div class="row" id="form_container">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                <form id="broadcast_form" enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" value="upload_broadcast" id="perform_action" name="perform_action" />
                    <input type="hidden" value="uploaded" id="update_as" name="update_as" />
                    <input type="hidden" value="" id="broadcast_id" name="broadcast_id" />
                    <input type="hidden" value="" id="stream_name" name="stream_name" />
                    <input type="hidden" value="{{ old('broadcast_image_name') }}" id="broadcast_image_name" name="broadcast_image_name"  />
                    <input type="hidden" value="{{ old('broadcast_video_name') }}" id="broadcast_video_name" name="broadcast_video_name"  />
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <input data-rule-required="true" class="form-control" type="text" value="{{ old('broadcast_title') }}" id="broadcast_title" name="broadcast_title" placeholder="Title" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <textarea class="form-control" id="broadcast_description" name="broadcast_description" placeholder="Description">{{ old('broadcast_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="broadcast_image">Upload Image</label>
                                <input class="filepond-input" type="file" value="" id="broadcast_image" name="broadcast_image" placeholder="Please select broadcast image" />
                                <div id="image_upload_loader" class="text-center text-success">
                                    <i class="fa fa-fw fa-cog fa-2x fa-spin"></i> <span>Processing Image</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="broadcast_video">Upload Video</label>
                                <input class="filepond-input" type="file" value="" id="broadcast_video" name="broadcast_video" placeholder="Please select broadcast video" />
                                <div id="video_upload_loader" class="text-center text-success">
                                    <i class="fa fa-fw fa-cog fa-2x fa-spin"></i> <span>Processing Video</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            @if(Auth::user()->hasPlugin(Auth::user()->id))
                                <div class="form-group label-cstm">
                                    <div class="styled-input-single">
                                        <input type="checkbox" name="post_plugin" id="embed" value="false" onclick="$(this).val(this.checked ? 'true' : 'false')" />
                                        <label for="embed">Embed into website</label>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if(Auth::user()->profile->youtube_auth_info != NULL)
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group label-cstm">
                                    <div class="styled-input-single">
                                        <input type="checkbox" name="stream_to_youtube" id="stream_to_youtube" value="yes"/>
                                        <label for="stream_to_youtube">Stream to youtube</label>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                <button type="button" onclick="" class="btn btn-lg btn-success" id="update_button">Publish <i id="upload-in-process" style="display:none" class="fa fa-fw fa-cog fa-spin"></i></button>
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

    <link href="{{ asset('assets/video-js-7.7.4/video-js.min.css') }}" rel="stylesheet" />
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

    <script src="{{ asset('assets/video-js-7.7.4/video.min.js') }}"></script>
    <script src="{{ asset('assets/video-js-7.7.4/videojs.contrib-hls.min.js') }}"></script>

    <script src="{{ asset('assets/webrtc/adapter-latest.js') }}"></script>
    <script src="{{ asset('assets/webrtc/webrtc_adaptor.js') }}"></script>
    <script>
        $(document).ready(function(){
            function checkbrowser() {
                var navUserAgent = navigator.userAgent;
                var browserName  = navigator.appName;
                var browserVersion  = ''+parseFloat(navigator.appVersion);
                var majorVersion = parseInt(navigator.appVersion,10);
                var tempNameOffset,tempVersionOffset,tempVersion;


                if ((tempVersionOffset=navUserAgent.indexOf("Opera"))!=-1) {
                browserName = "Opera";
                browserVersion = navUserAgent.substring(tempVersionOffset+6);
                if ((tempVersionOffset=navUserAgent.indexOf("Version"))!=-1)
                browserVersion = navUserAgent.substring(tempVersionOffset+8);
                } else if ((tempVersionOffset=navUserAgent.indexOf("MSIE"))!=-1) {
                browserName = "Microsoft Internet Explorer";
                browserVersion = navUserAgent.substring(tempVersionOffset+5);
                } else if ((tempVersionOffset=navUserAgent.indexOf("Chrome"))!=-1) {
                browserName = "Chrome";
                browserVersion = navUserAgent.substring(tempVersionOffset+7);
                } else if ((tempVersionOffset=navUserAgent.indexOf("Safari"))!=-1) {
                browserName = "Safari";
                browserVersion = navUserAgent.substring(tempVersionOffset+7);
                if ((tempVersionOffset=navUserAgent.indexOf("Version"))!=-1)
                browserVersion = navUserAgent.substring(tempVersionOffset+8);
                } else if ((tempVersionOffset=navUserAgent.indexOf("Firefox"))!=-1) {
                browserName = "Firefox";
                browserVersion = navUserAgent.substring(tempVersionOffset+8);
                } else if ( (tempNameOffset=navUserAgent.lastIndexOf(' ')+1) < (tempVersionOffset=navUserAgent.lastIndexOf('/')) ) {
                browserName = navUserAgent.substring(tempNameOffset,tempVersionOffset);
                browserVersion = navUserAgent.substring(tempVersionOffset+1);
                if (browserName.toLowerCase()==browserName.toUpperCase()) {
                browserName = navigator.appName;
                }
                }

                // trim version
                if ((tempVersion=browserVersion.indexOf(";"))!=-1)
                browserVersion=browserVersion.substring(0,tempVersion);
                if ((tempVersion=browserVersion.indexOf(" "))!=-1)
                browserVersion=browserVersion.substring(0,tempVersion);
                return browserName+" "+browserVersion;

            }
            $('#image_upload_loader').hide();
            $('#video_upload_loader').hide();
            $("#update_button").attr("disabled", true);
            $('body').on('click', '#update_button', function(){
                if($('#broadcast_form').valid()) {
                    
                    $('#upload-in-process').show();
                    $("#update_button").attr("disabled", true);
                    var form_data = $('#broadcast_form').serialize();
                    form_data += "&meta_info=" + checkbrowser();

                    var my_request;
                    my_request = $.ajax({
                        url: "{{ route('broadcasts.ajax') }}",
                        method: 'POST',
                        data: form_data
                    });

                    my_request.done(function(response) {
                        res =  response.status ?  response : JSON.parse(response);
                        $('#upload-in-process').hide();
                        $("#update_button").attr("disabled", false);
                        if(res.status == 'success'){
                            window.location = "{{ route('broadcasts.index') }}";
                        }
                    });

                    my_request.error(function(){
                        $('#upload-in-process').hide();
                        $("#update_button").attr("disabled", false);
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
                            $("#update_button").removeAttr("disabled");
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

    </script>
@endpush
