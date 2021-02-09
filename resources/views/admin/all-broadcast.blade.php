@php
    use Illuminate\Support\Carbon;
@endphp
@extends('admin.master-layout')
@push('admin-css')
    <style>
        .view-metadata{
            margin-top: 20px;
        }
        .btn-metainfo{
            margin-left: -20px;
        }
        .del-all-bc-single{
            margin-top: 10px;
        }
    </style>
@endpush
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<link href="{{ asset('assets/video-js-7.7.4/video-js.min.css') }}" rel="stylesheet" />
<script type="text/javascript" src="https://player.wowza.com/player/latest/wowzaplayer.min.js"></script>
<script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>

@php
    $ipArr = array(0 => '52.18.33.132', 1 => '52.17.132.36');
    $index = rand(0,1);
    $ip =  env('APP_ENV') == 'local' ? '192.168.20.251' : $ipArr[$index];
@endphp
     <!--Right Content Area start-->
     <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12" id="height-section">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="section-heading">
                        <p>All Broadcasts</p>
                        <div class="all-bc-search">
                        <form action="{{route('admin.broadcast')}}">
                                <input name="username" type="text" placeholder="Search Username" value="{{ isset($_GET['username']) ? $_GET['username'] : '' }}"/>
                                <input name="search" type="text" placeholder="Search broadcast..." value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}"/>
                                <input type="text" name="datetimes" value="{{ isset($_GET['datetimes']) ? $_GET['datetimes'] : '' }}"/>
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>

                <br>
                <div class="col-sm-12">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session('flash_message'))
                        <div class="alert alert-success">{{ session('flash_message') }}</div>
                    @endif
                </div>

                <!--Reported Broadcost listing start-->

                @php
                    $ipArr = array(0 => '52.18.33.132', 1 => '52.17.132.36');
                    $index = rand(0,1);
                    $ip =  env('APP_ENV') == 'local' ? '192.168.20.251' : $ipArr[$index];

                @endphp
                @if(isset($broadcasts) && !empty($broadcasts))
                @foreach ($broadcasts as $broadcast)
                    @php
                   // dd($broadcast);
                    $image_classes = '';
                    $b_image = !empty($broadcast->broadcast_image) ? $broadcast->broadcast_image : public_path('images/default001.jpg');
                    $b_id = $broadcast->id;
                    $b_title = !is_null($broadcast->title) && !empty($broadcast->title) ? $broadcast->title : 'Untitled';

                    $share_url = !empty($broadcast->share_url) ? $broadcast->share_url : route('broadcast.view', $broadcast->id);
                    $b_description = !is_null($broadcast->description) ? $broadcast->description : '';

                    $video_file_name = $broadcast->filename;
                    if(!$b_image){
                        $b_image = public_path('images/default001.jpg');
                    }
                    if($video_file_name){
                        $image_classes = 'has_video';
                    }
                    $status = $broadcast->status;
                    // dd($b_image);
                    @endphp
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="listing-reported-broadcost">
                        @if(!empty($broadcast->filename))
                            <a class="reporting-bc-image" data-fancybox data-src="#video_player_{{ $broadcast->id }}" href="javascript:;">
                                @php
                                        $thumbnail_image = !is_null($broadcast->broadcast_image) ? $broadcast->broadcast_image : '';
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
                                            <img onerror="this.onerror=null;this.src='/images/default001.jpg';" src="{{ asset('images/broadcasts/' . $broadcast->user_id . '/' . $thumbnail_image) }}" alt="{{ $b_title }}" />
                                        @endif

                                    @else
                                        <img onerror="this.onerror=null;this.src='/images/default001.jpg';" src="{{ getBroadcastThumbnail($broadcast) }}" alt="{{ $b_title }}" />
                                    @endif
                                            <span class="play-report-icon">
                                                <i class="fa fa-play"></i>
                                            </span>
                                <div class="overlay"></div>
                            </a>
                        @else
                        <a href="javascript:streamFileDoesNotExist();" class="pop-report-bc-link">
                            <div class="reporting-bc-image">
                                @php

                                        $thumbnail_image = !is_null($broadcast->broadcast_image) ? $broadcast->broadcast_image : '';
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
                                            <img onerror="this.onerror=null;this.src='/images/default001.jpg';" src="{{ $thumbnail_image }}" alt="{{ $b_title }}" />
                                        @else
                                            <img onerror="this.onerror=null;this.src='/images/default001.jpg';" src="{{ asset('images/broadcasts/' . $broadcast->user_id . '/' . $thumbnail_image) }}" alt="{{ $b_title }}" />
                                        @endif

                                    @else
                                        <img onerror="this.onerror=null;this.src='/images/default001.jpg';" src="{{ getBroadcastThumbnail($broadcast) }}" alt="{{ $b_title }}" />
                                    @endif
                                            <span class="play-report-icon">
                                                <i class="fa fa-play"></i>
                                            </span>
                                <div class="overlay"></div>
                            </div>
                        </a>
                        @endif

                        <div class="reported-bc-detail">
                            <p><span class="title btitle">{{ ucwords($b_title) }}</span></p>
                            <p><span class="postby">Posted By : </span> <span class="report-result-display"> <a href="{{url('admin/broadcast')}}?username={{ $broadcast->user['username'] }}">{{ $broadcast->user['username'] }}</a> </span></p>
                            <p><span class="reportby">Status :</span> <span class="report-result-display"> {{ $broadcast->status }} </span></p>
                            <p>
                                <span class="reportdate">Source :</span>
                                <span class="report-result-display">


                                    <a href="{{ $share_url }}" target="_blank">
                                        {{ $share_url }}
                                    </a>
                                </span>
                            </p>

                            @if(isset($_GET['dev']))
                                <p>  <span class="reportdate">Stream :</span> <span class="report-result-display"> <a href="{{ $broadcast->dynamic_stream_url_web }}"> {{ $broadcast->dynamic_stream_url_web }} </a> </span></p>
                            @endif

                            <p>  <span class="reportdate">Views :</span> <span class="report-result-display"> {{ !is_null($broadcast->view_count) ? $broadcast->view_count : 0 }} </span></p>

                            <p>  <span class="reportdate">Timestamp :</span> <span class="report-result-display"> <?php echo date("Y-d-m H:i:s", strtotime($broadcast->timestamp));?> </span></p>
                            <p>  <span class="reportdate">Date :</span> <span class="report-result-display"> <?php echo date("d M Y", strtotime($broadcast->created_at));?> </span></p>
                            <p>  <span class="reportdate">Video Size :</span> <span class="report-result-display"> <?php echo formatBytes($broadcast->broadcast_size, 0);?> </span></p>
                            <hr />
                            {{-- <pre>
                                @php echo json_encode($broadcast, JSON_PRETTY_PRINT) @endphp
                            </pre> --}}
                        </div>

                        <div class="report-bc-action-div">
                            <div class="row">
                                <div class="col-xs-12 text-center">
                                    <div style="width: 50px; height: 50px; display:inline-block;">
                                        <img id="fileexist{{$broadcast->id }}" src="{{ asset("images/document-remove-icon.png")}}" class="img-responsive" />
                                    </div>
                                </div>
                            </div>
                            @if(isset($broadcast->metaInfo['meta_info']) && !empty($broadcast->metaInfo['meta_info']))
                            <div class="col-xs-12 text-center view-metadata">
                                <div style="width: 50px; height: 50px; display:inline-block;">
                                <button type="button" class="btn btn-primary btn-metainfo" title="Meta Data" data-toggle="modal" data-target="#product_view_{{ $broadcast->id }}">
                                        Meta Info
                                        {{-- <i class="fa fa-tags "></i> --}}
                                    </button>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-xs-12">

                                    <form method="post" action="{{ route('admin.deletebroadcast') }}" id="form-{{ $broadcast->id }}">
                                        @csrf
                                        <input type="hidden" name="broadcast_id" value="{{ $broadcast->id }}">
                                        <input type="hidden" name="broadcast_delete_reason" id="broadcast_delete_reason" value="">
                                        <input type="button" name="" class="btn btn-danger delete-block-bc del-all-bc-single" value="Delete" onclick="confirmDelete({{ $broadcast->id }})">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(isset($broadcast->metaInfo['meta_info']) && !empty($broadcast->metaInfo['meta_info']))
                    <div class="modal fade product_view" id="product_view_{{ $broadcast->id }}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <a href="#" data-dismiss="modal" class="class pull-right"><i class="fa fa-close fa-lg"></i></a>
                                    <h3 class="modal-title">Meta Data</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12 product_img">
                                            <table id="mytable" class="table table-bordred table-striped">
                                                @php
                                                    $meta_data = json_decode($broadcast->metaInfo['meta_info']);
                                                    if(is_string($meta_data)){
                                                        $meta_data = json_decode($meta_data);
                                                    }
                                                    if(is_object($meta_data)){
                                                    $isConnectionExpensive = isset($meta_data->connectionDetail->isConnectionExpensive) && $meta_data->connectionDetail->isConnectionExpensive == 'false' ? 0 : 1;

                                                @endphp

                                                <tr>
                                                    <th colspan="2" class="text-center">Meta Info Detail</th>
                                                </tr>
                                                <tr>
                                                    <th>Brand</th>
                                                    <th>  {{ isset($meta_data->brand) ? $meta_data->brand : '' }} </th>
                                                </tr>
                                                <tr>
                                                    <th>System Name</th>
                                                    <th> {{ isset($meta_data->systemName) ? $meta_data->systemName : '' }} </th>
                                                </tr>
                                                <tr>
                                                    <th>deviceName</th>
                                                    <th> {{ isset($meta_data->deviceName) ? $meta_data->deviceName : '' }} </th>
                                                </tr>
                                                <tr>
                                                    <th>systemVersion</th>
                                                    <th> {{ isset($meta_data->systemVersion) ? $meta_data->systemVersion : '' }} </th>
                                                </tr>
                                                <tr>
                                                    <th>deviceType</th>
                                                    <th> {{ isset($meta_data->deviceType) ? $meta_data->deviceType : '' }} </th>
                                                </tr>
                                                <tr>
                                                    <th>apiLevel</th>
                                                    <th> {{ isset($meta_data->apiLevel) ? $meta_data->apiLevel : '' }} </th>
                                                </tr>
                                                <tr>
                                                    <th>timeZone</th>
                                                    <th> {{ isset($meta_data->timeZone) ? $meta_data->timeZone : '' }} </th>
                                                </tr>
                                                <tr>
                                                    <th>connectionType</th>
                                                    <th> {{ isset($meta_data->connectionType) ? $meta_data->connectionType : '' }} </th>
                                                </tr>
                                                <tr>
                                                    <th>isConnected</th>
                                                    <th> {{ isset($meta_data->isConnected) ? $meta_data->isConnected : '' }} </th>
                                                </tr>
                                                <tr>
                                                    <th>connectionDetail</th>
                                                    <th> {{ $isConnectionExpensive }} </th>
                                                </tr>
                                                <tr>
                                                    <th>internetSpeed</th>
                                                    <th> {{ isset($meta_data->internetSpeed) ? $meta_data->internetSpeed : ''}} </th>
                                                </tr>

                                                <tr>
                                                    <th>Endpoint URl</th>
                                                    <th>{{ isset($broadcast->metaInfo['endpoint_url']) ? $broadcast->metaInfo['endpoint_url'] : '' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Plugins Connected</th>
                                                    <th>
                                                    @if(isset($broadcast->user->plugins))
                                                    @foreach($broadcast->user->plugins as $plugin)
                                                    {{  $plugin->type." : ".$plugin->url }}<br>
                                                    @endforeach
                                                    @endif
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Created At</th>
                                                    <th>
                                                        {{ isset($broadcast->metaInfo['created_at']) ? (new Carbon($broadcast->metaInfo['created_at']))->diffForHumans() : '' }}
                                                    </th>
                                                </tr>
                                                @php
                                                    }else{
                                                @endphp
                                                <tr>
                                                    <td>
                                                       No Meta info
                                                    </td>
                                                </tr>
                                                @php
                                                    }
                                                @endphp
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div id="video_player_{{ $broadcast->id }}" style="display: none;" class="row">

                                    @php
                                    $image_classes = '';

                                    $status = $broadcast->status;

                                    $video_file_name = !is_null($broadcast->filename) ? $broadcast->filename : '';

                                    if($video_file_name){
                                        $image_classes = 'has_video';
                                    }

                                @endphp
                                @if($video_file_name)
                                        @if($broadcast->status == 'online')
                                            <video
                                                style="margin: auto;"
                                                id="my_live_player_{{ $broadcast->id }}"
                                                class="video-js vjs-big-play-centered"
                                                controls
                                                preload="auto"
                                                bid="{{ $broadcast->id }}"
                                                poster="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . Auth::id() . '/' . $broadcast->broadcast_image) : getBroadcastThumbnail($broadcast) }}"
                                                data-setup='{"fluid": false}'>
                                                <source src="{{ ANT_MEDIA_SERVER_STAGING_URL . WEBRTC_APP .'/streams/' . pathinfo($broadcast->video_name, PATHINFO_FILENAME) . '.m3u8' }}" type="application/x-mpegURL"></source>
                                                <p class="vjs-no-js">
                                                    To view this video please enable JavaScript, and consider upgrading to a
                                                    web browser that
                                                    <a href="https://videojs.com/html5-video-support/" target="_blank">
                                                    supports HTML5 video
                                                    </a>
                                                </p>
                                            </video>
                                        @else
                                            <video
                                                style="margin: auto;"
                                                id="my_recorded_player_{{ $broadcast->id }}"
                                                class="video-js vjs-big-play-centered"
                                                controls
                                                preload="auto"
                                                bid="{{ $broadcast->id }}"
                                                poster="{{ !empty($broadcast->broadcast_image) ?  asset('images/broadcasts/' . Auth::id() . '/' . $broadcast->broadcast_image) : getBroadcastThumbnail($broadcast) }}"
                                                data-setup='{"fluid": false}'>
                                                <source src="{{ getVideoUrl($broadcast) }}" type="video/mp4"></source>
                                                <p class="vjs-no-js">
                                                    To view this video please enable JavaScript, and consider upgrading to a
                                                    web browser that
                                                    <a href="https://videojs.com/html5-video-support/" target="_blank">
                                                    supports HTML5 video
                                                    </a>
                                                </p>
                                            </video>
                                        @endif

                                @endif

                    </div>
                </div>

                @endforeach
                @endif
                <!--Reported Broadcost listing End-->

                <!--Pagination start-->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="report-bc-pagination">
                        <nav>
                            {{$broadcasts->appends(request()->query())->links()}}
                        </nav>
                    </div>
                </div>
                <!--Pagination End-->
            </div>
        </div>
        <!--Right Content Area End-->

        <!-- Modal -->
        <div class="modal fade" id="deleteReasonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Video Reason</h5>
                        <button type="button" class="close save_reason" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Modify Reason</label>
                                <textarea class="form-control" id="video_delete_reason" rows="8"><?php echo html_entity_decode('We would like to inform you that your [video_title] has been deleted from Hapity Platform because, it violates our fair use policy.  For more info please read our <a hrf="https://www.hapity.com/privacy-policy">Privacy Policy</a>'); ?>
                                </textarea>
                                <input type="hidden" id="selected_delete_video_id" value="">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary save_reason" data-dismiss="modal">Save Reason</button>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('admin-script')
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="{{ asset('assets/video-js-7.7.4/video.min.js') }}"></script>
    <script src="{{ asset('assets/video-js-7.7.4/videojs-http-streaming.min.js') }}"></script>

    <script type="text/javascript">
        function closeModel(id){
            var my_player = WowzaPlayer.get('w-broadcast-'+id);
            if(my_player !== null){
                my_player.finish();
            }
            console.log(my_player);
        }
        $('#myModal').on('hidden.bs.modal', function () {
        // do something…
        });

        function streamFileDoesNotExist() {
            alertify.error('Stream File Does Not Exist!');
        }

        function confirmDelete(broadcast_id){
            alertify.confirm('Are you sure you want to delete? ',function(e){
            if(e) {
                setTimeout(function(){ delete_video_reason(broadcast_id); }, 0);
            } else {
                return false;
            }
        }).setHeader('<em> Delete Broadcast</em> ').set('labels', {ok:'Yes', cancel:'Cancel'});
    }

    function delete_video_reason (broadcast_id) {
        jQuery.noConflict();
        $('#selected_delete_video_id').val(broadcast_id);
        $('#deleteReasonModal').modal('show');
    }

    $('.save_reason').on('click', function () {
        var delete_reason = $('#video_delete_reason').val();
        var broadcast_id = $('#selected_delete_video_id').val();

        $('#broadcast_delete_reason').val(delete_reason);

        $('#form-'+broadcast_id).submit();
        return true;
    });

    $("video source").each(function(){

        var url = $(this).attr('src');
        url.replace(".m3u8",".mp4");
        var that = $(this);
        var id = that.parent().attr('bid');
        $.ajax({
            url: url,
            type:'HEAD',
            error: function()
            {
                console.log("not exist: "+url)
            },
            success: function()
            {
                $('#fileexist'+id).attr('src', '{{ asset("images/document-tick-icon.png") }}');
                that.attr('src',url)
            }
        });
    })
    </script>
@endpush
