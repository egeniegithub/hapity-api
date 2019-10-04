@php 
    $ipArr = array(0 => '52.18.33.132', 1 => '52.17.132.36');
    $index = rand(0,1);
    $ip = $ipArr[$index];
@endphp
@extends('admin.master-layout')
@push('admin-css')

@endpush
@section('content')
     <!--Right Content Area start-->
     <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12" id="height-section">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="section-heading">
                        <p>All Broadcasts</p>
                        <div class="all-bc-search">
                        <form action="{{url('admin/broadcast')}}">
                                <input name="search" type="text" placeholder="Search broadcast..."/>
                                <input type="text" name="datetimes" />
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>

                <!--Reported Broadcost listing start-->

                @php
                // $ipArr = array(0 => '52.18.33.132', 1 => '52.17.132.36');
                // $index = rand(0,1);
                $ip = '52.18.33.132';//$ipArr[$index];  
                @endphp
                @if(isset($broadcasts) && !empty($broadcasts))
                @foreach ($broadcasts as $broadcast) 
                    @php 

                    $image_classes = '';
                    $b_image = !empty($broadcast->broadcast_image) ? $broadcast->broadcast_image : 'https://www.hapity.com/images/default001.jpg';
                    // echo "<pre>";
                    // print_r($broadcast);
                    // echo "</pre>"; exit;
                    $b_id = $broadcast->id;
                    
                    if($broadcast->title){
                        $b_title = $broadcast->title;
                    } else {
                        $b_title = "Untitled";
                    }
                    $stream_url = $broadcast->stream_url;

                    $video_file_name = $broadcast->filename;
                    if(!$b_image){
                        $b_image = 'https://www.hapity.com/images/default001.jpg';
                    }
                    if($video_file_name){
                        $image_classes = 'has_video';
                    }
                    $status = $broadcast->status;
                    @endphp
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="listing-reported-broadcost">
                        <a href="javascript:;" class="pop-report-bc-link" id="<?php echo ucwords($broadcast->title); ?>" data-toggle="modal" data-target="#broadcastModel-<?php echo ($broadcast->id); ?>">
                            <div class="reporting-bc-image">
                                <img src="<?php echo $broadcast->broadcast_image; ?>"/>
                                            <span class="play-report-icon">
                                                <i class="fa fa-play"></i>
                                            </span>
                                <div class="overlay"></div>
                            </div>
                        </a>

                        <div class="reported-bc-detail">
                            <p> <span class="title btitle">{{ ucwords($broadcast->title) }}</span></p>
                            <p> <span class="postby">Posted By : </span> <span class="report-result-display"> {{ $broadcast['user']['username'] }} </span></p>
                            <p>  <span class="reportby">Status :</span> <span class="report-result-display"> {{ $broadcast->status }} </span></p>
                            <p>  <span class="reportdate">Source :</span> <span class="report-result-display"> <a href="{{ $broadcast->share_url }}">{{ $broadcast['share_url'] }}</a> </span></p>

                            @if(isset($_GET['dev']))
                                <p>  <span class="reportdate">Stream :</span> <span class="report-result-display"> <a href="<?php echo $broadcast->stream_url;?>">{{ $broadcast['stream_url'] }}</a> </span></p>
                            @endif
                            <?php $stream_count = "https://api.hapity.com/webservice/stream_count?id=".$broadcast->id; ?>
                            <p>  <span class="reportdate">Views :</span> <span class="report-result-display"> <?php  echo file_get_contents($stream_count); ?> </span></p>
                            
                            <p>  <span class="reportdate">Date :</span> <span class="report-result-display"> <?php echo date("d M Y", strtotime($broadcast->created_at));?> </span></p>
                        </div>

                        <div class="report-bc-action-div">
                        <a href="{{url('deletebroadcast'.'/'.$broadcast->id)}}" class="delete-block-bc del-all-bc-single">Delete</a>
                        </div>
                    </div>
                    <div class="modal fade" id="broadcastModel-<?php echo ($broadcast->id); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-video" id="model-cross" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel"><?php echo ucwords($broadcast->title); ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div id="broadcast-<?php echo $broadcast->id;?>" class="player"></div>
                                    <script type="text/javascript">
                                        jwplayer("broadcast-<?php echo $broadcast->id;?>").setup({
                                            sources: [{
                                                file: "<?php  if($status == "online")
                                             echo str_replace("rtsp","rtmp",$stream_url);
                                             else
                                             echo "rtmp://".$ip.":1935/vod/".$video_file_name;?>"
                                            },{
                                                file:"<?php  if($status == "online")
                                             echo str_replace(array("rtsp","rtmp"),"http",$stream_url);
                                             else
                                             echo "http://".$ip.":1935/vod/".$video_file_name;?>"
                                            }],
                                            playButton: 'https://www.hapity.com/images/play.png',
                                            height: 380,
                                            width: "100%",
                                            image: '<?php echo $b_image; ?>',
                                            skin: 'stormtrooper',
                                        });
                                        <?php /**/ ?>
                                        // jwplayer("broadcast-<?php echo $broadcast->id;?>").setup({
                                        //     sources: [{
                                        //         file: "rtmp://52.18.33.132:1935/vod/1012135211551959177802.stream"
                                        //     },{
                                        //         file:"http://52.18.33.132:1935/vod/1012135211551959177802.stream/playlist.m3u8"
                                        //     }],
                                        //     playButton: 'https://www.hapity.com/images/play.png',
                                        //     height: 380,
                                        //     width: "100%",
                                        //     image: 'https://api.hapity.com/uploads/broadcast_images/1551959322.jpg',
                                        //     skin: 'stormtrooper',
                                        // });
                                    </script>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default close-video" id="close-btn" data-dismiss="modal">Close</button>
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

@endpush