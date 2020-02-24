
@extends('admin.master-layout')
@push('admin-css')

@endpush
@section('content')

<script type="text/javascript" src="https://player.wowza.com/player/latest/wowzaplayer.min.js"></script>

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
                                <input name="search" type="text" placeholder="Search broadcast..."/>
                                <input type="text" name="datetimes" />
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

                    $image_classes = '';
                    $b_image = !empty($broadcast->broadcast_image) ? $broadcast->broadcast_image : public_path('images/default001.jpg');

                    $b_id = $broadcast['id'];

                    if($broadcast['title']){
                        $b_title = $broadcast['title'];
                    } else {
                        $b_title = "Untitled";
                    }
                    $file_info = pathinfo($broadcast['filename']);

                    $file_ext = isset($file_info['extension']) ? $file_info['extension'] : 'mp4';

                    $share_url = !empty($broadcast['share_url']) ? $broadcast['share_url'] : route('broadcast.view', $broadcast['id']);
                    $b_description = $broadcast['description'];

                    $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';
                    $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';

                    $stream_url = urlencode('https://' . $ip .  ':1935/' . $vod_app .  '/' . $file_ext . ':' .  $broadcast['filename'] . '/playlist.m3u8') ;
                    if($broadcast->status == 'online') {
                        $file = pathinfo($broadcast['filename'], PATHINFO_FILENAME );
                        $stream_url = urlencode('rtmp://' . $ip .  ':1935/' . $live_app . '/' .  $file . '/playlist.m3u8') ;
                    }

                    // $stream_url = urlencode('https://' . $ip .  ':1935/' . $vod_app .  '/' . $file_ext . ':' .  $broadcast['stream_url'] . '/playlist.m3u8') ;
                    // $stream_url = $broadcast['stream_url'];

                    $video_file_name = $broadcast['filename'];
                    if(!$b_image){
                        $b_image = public_path('images/default001.jpg');
                    }
                    if($video_file_name){
                        $image_classes = 'has_video';
                    }
                    $status = $broadcast['status'];
                    // dd($b_image);
                    @endphp
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="listing-reported-broadcost">
                        @if($broadcast->file_exists)
                        <a href="javascript:;" class="pop-report-bc-link" id="<?php echo ucwords($broadcast['title']); ?>" data-toggle="modal" data-target="#broadcastModel-<?php echo ($broadcast['id']); ?>">
                            <div class="reporting-bc-image">
                                @php
                                        $thumbnail_image = $broadcast['broadcast_image'];
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
                                            <img src="{{ asset('images/broadcasts/' . $broadcast['user_id'] . '/' . $thumbnail_image) }}" alt="{{ $b_title }}" />
                                        @endif

                                    @else
                                        <img src="{{ asset('images/default001.jpg') }}" alt="{{ $b_title }}" />
                                    @endif
                                            <span class="play-report-icon">
                                                <i class="fa fa-play"></i>
                                            </span>
                                <div class="overlay"></div>
                            </div>
                        </a>
                        @else
                        <a href="javascript:streamFileDoesNotExist();" class="pop-report-bc-link">
                            <div class="reporting-bc-image">
                                @php
                                        $thumbnail_image = $broadcast['broadcast_image'];
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
                                            <img src="{{ asset('images/broadcasts/' . $broadcast['user_id'] . '/' . $thumbnail_image) }}" alt="{{ $b_title }}" />
                                        @endif

                                    @else
                                        <img src="{{ asset('images/default001.jpg') }}" alt="{{ $b_title }}" />
                                    @endif
                                            <span class="play-report-icon">
                                                <i class="fa fa-play"></i>
                                            </span>
                                <div class="overlay"></div>
                            </div>
                        </a>
                        @endif

                        <div class="reported-bc-detail">
                            <p><span class="title btitle">{{ ucwords($broadcast['title']) }}</span></p>
                            <p><span class="postby">Posted By : </span> <span class="report-result-display"> {{ $broadcast['username'] }} </span></p>
                            <p><span class="reportby">Status :</span> <span class="report-result-display"> {{ $broadcast['status'] }} </span></p>
                            <p>
                                <span class="reportdate">Source :</span>
                                <span class="report-result-display">


                                    <a href="{{ $share_url }}" target="_blank">
                                        {{ $share_url }}
                                    </a>
                                </span>
                            </p>

                            @if(isset($_GET['dev']))
                                <p>  <span class="reportdate">Stream :</span> <span class="report-result-display"> <a href="<?php echo $broadcast['stream_url'];?>">{{ $broadcast['stream_url'] }}</a> </span></p>
                            @endif

                            <p>  <span class="reportdate">Views :</span> <span class="report-result-display"> {{ !is_null($broadcast->view_count) ? $broadcast->view_count : 0 }} </span></p>

                            <p>  <span class="reportdate">Date :</span> <span class="report-result-display"> <?php echo date("d M Y", strtotime($broadcast['created_at']));?> </span></p>
                            <hr />
                            <pre>
                                @php echo json_encode($broadcast, JSON_PRETTY_PRINT) @endphp
                            </pre>
                        </div>

                        <div class="report-bc-action-div">
                            <div class="row">
                                <div class="col-xs-12 text-center">
                                    <div style="width: 50px; height: 50px; display:inline-block;">
                                        <img src="{{ $broadcast->file_exists ? asset('images/document-tick-icon.png') : asset('images/document-remove-icon.png') }}" class="img-responsive" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <form method="post" action="{{ route('admin.deletebroadcast') }}" id="form-{{ $broadcast['id'] }}">
                                        @csrf
                                        <input type="hidden" name="broadcast_id" value="{{ $broadcast['id'] }}">
                                        <input type="button" name="" class="btn btn-danger delete-block-bc del-all-bc-single" value="Delete" onclick="confirmDelete({{ $broadcast['id'] }})">
                                    </form>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="modal fade" id="broadcastModel-<?php echo ($broadcast['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-video" id="model-cross" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" id="{{ $broadcast['id'] }}" onClick="closeModel(this.id)">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel"><?php echo ucwords($broadcast['title']); ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div id="broadcast-<?php echo $broadcast['id'];?>" class="player"></div>

                                    @php
                                    $image_classes = '';
                                    $b_image = '';
                                    // $broadcast->broadcast_image;
                                    $b_id = isset($broadcast['id']) ? $broadcast['id'] : '';

                                    if($broadcast['title']){
                                        $b_title = $broadcast['title'];
                                    } else {
                                        $b_title = "Untitled";
                                    }

                                    $file_info = pathinfo($broadcast['filename']);

                                    $file_ext = isset($file_info['extension']) ? $file_info['extension'] : 'mp4';

                                    $share_url = $broadcast['share_url'];
                                    $b_description = $broadcast['description'];

                                    $vod_app = env('APP_ENV') == 'staging' ? 'stage_vod' : 'vod';
                                    $live_app = env('APP_ENV') == 'staging' ? 'stage_live' : 'live';

                                    $file_name = $broadcast['filename'];
                                    if ($file_ext == 'stream' || $file_ext == 'stream_160p' || $file_ext == 'stream_360p') {
                                        $file_name = $broadcast['filename'] . '.mp4';
                                        $file_ext = 'mp4';
                                    }

                                    $stream_url = urlencode('https://media.hapity.com/' . $vod_app .  '/_definst_/' . $file_ext . ':' .  $file_name . '/playlist.m3u8') ;
                                    if($broadcast['status'] == 'online') {
                                        $file = pathinfo($broadcast['filename'], PATHINFO_FILENAME );
                                        $stream_url = urlencode('https://media.hapity.com/' . $live_app . '/' .  $file . '/playlist.m3u8') ;
                                    }
                                    //http://[wowza-ip-address]:1935/vod/mp4:sample.mp4/playlist.m3u8
                                    //rtmp%3A%2F%2F192.168.20.251%3A1935%2Flive%2F132041201998908.stream.mp4%2Fplaylist.m3u8
                                    //rtmp%3A%2F%2F192.168.20.251%3A1935%2Flive%2F132041201998908.stream%2Fplaylist.m3u8
                                    //https://media.hapity.com/stage_vod/_definst_/mp4:8e192b3711cfd29cafe41297d9fa725b.stream.mp4/playlist.m3u8

                                    //echo $stream_url;

                                    $status = $broadcast['status'];

                                    $video_file_name = $broadcast['filename'];

                                    if(!$b_image){
                                        $b_image = 'default001.jpg';
                                    }

                                    if($video_file_name){
                                        $image_classes = 'has_video';
                                    }

                                @endphp
                                @if($video_file_name)
                                <div class="video-container video-conteiner-init">
                                    <div id="w-broadcast-{{ $b_id }}" style="width:100%; height:0; padding:0 0 56.25% 0"></div>
                                </div>
                                <script>
                                    WowzaPlayer.create('w-broadcast-{{ $b_id }}',
                                    {
                                        "license":"PLAY1-fMRyM-nmUXu-Y79my-QYx9R-VFRjJ",
                                        "title":"{{ $b_title }}",
                                        "description":"{{ $b_description }}",
                                        //"sourceURL":"rtmp%3A%2F%2F52.18.33.132%3A1935%2Fvod%2F9303fbcdfa4490cc6d095988a63b44df.stream",
                                        "sourceURL":"{{ $broadcast->dynamic_stream_url_web }}",
                                        "autoPlay":false,
                                        "volume":"75",
                                        "mute":false,
                                        "loop":false,
                                        "audioOnly":false,
                                        "uiShowQuickRewind":true,
                                        "uiQuickRewindSeconds":"30"
                                        }
                                    );

                                </script>
                                @endif

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default close-video close-btn" id="{{ $b_id }}" onClick="closeModel(this.id)" data-dismiss="modal" >Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
                <!--Reported Broadcost listing End-->

                <!--Pagination start-->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="report-bc-pagination">
                        <nav>
                            {{$broadcasts->links()}}
                        </nav>
                    </div>
                </div>
                <!--Pagination End-->
            </div>
        </div>
        <!--Right Content Area End-->
@endsection

@push('admin-script')
    <script type="text/javascript">
        function closeModel(id){
            var my_player = WowzaPlayer.get('w-broadcast-'+id);
            if(my_player !== null){
                my_player.finish();
            }
            console.log(my_player);
        }

        function streamFileDoesNotExist() {
            alertify.error('Stream File Does Not Exist!');
        }

        function confirmDelete(broadcast_id){
            alertify.confirm('Are you sure you want to delete? ',function(e){
            if(e) {
                $('#form-'+broadcast_id).submit();
                return true;
            } else {
                return false;
            }
        }).setHeader('<em> Delete Broadcast</em> ').set('labels', {ok:'Yes', cancel:'Cancel'});
    }

    </script>
@endpush
