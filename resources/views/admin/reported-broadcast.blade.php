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
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="section-heading">
                            <p> Reported Broadcasts</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            @if (Session::has('flash_message'))
                                <div class="alert alert-success">{{ Session::get('flash_message') }}</div>
                            @endif
                            @if(Session::has('flash_message_delete'))
                                <div class="alert alert-danger">{{ Session::get('flash_message_delete') }}</div>
                            @endif
                            
                        </div>
                    </div>
                    <!--Reported Broadcost listing start-->
                    <?php foreach ($reported_broadcasts as $broadcast) { ?>
                        
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="listing-reported-broadcost">
                            <a href="javascript:;" class="pop-report-bc-link" id="<?php echo ucwords($broadcast['broadcast']['title']); ?>" data-toggle="modal" data-target="#broadcastModel-<?php echo ($broadcast['id']); ?>">
                                <div class="reporting-bc-image">
                                    <img src="<?php echo $broadcast['broadcast']['broadcast_image']; ?>"/>
                                                <span class="play-report-icon">
                                                    <i class="fa fa-play"></i>
                                                </span>
                                    <div class="overlay"></div>
                                </div>
                            </a>

                            <div class="reported-bc-detail">
                                <p> <span class="title"><?php echo ucwords($broadcast['broadcast']['title']) ;?></span></p>
                                <p> <span class="postby">Posted By : </span> <span class="report-result-display"> <?php echo $broadcast['broadcast']['user']['username'] ;?></span></p>
                                <p>  <span class="reportby">Reports :</span> <span class="report-result-display"> {{ !empty($broadcast['broadcast']['userWithReportedUser']['reportedUser']) ? count($broadcast['broadcast']['userWithReportedUser']['reportedUser']) : 0 }}</span></p>
                                <p>  <span class="reportdate">Date :</span> <span class="report-result-display"> <?php echo $broadcast['broadcast']['created_at'] ;?></span></p>
                            </div>

                            <div class="report-bc-action-div">
                                <a href="{{ route('admin.reportBroadcastApproved',$broadcast['id']) }}" class="approve-block-bc">Approve</a>
                            <a href="{{route('admin.reportBroadcastDelete',$broadcast['id'])}}"  class="delete-block-bc">Delete</a>
                            </div>
                        </div>
                        <!-- Modal start -->
                        <div class="modal fade" id="broadcastModel-<?php echo ($broadcast['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" id="model-cross" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel"><?php echo ucwords($broadcast['broadcast']['title']); ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="broadcast-<?php echo $broadcast['id'];?>" class="player"></div>
                                        <script type="text/javascript">
                                            jwplayer("broadcast-<?php echo $broadcast['id'];?>").setup({
                                                sources: [{
                                                    file: "<?php  if($broadcast['broadcast']['status'] == "online")
                                                 echo str_replace("rtsp","rtmp",$broadcast['broadcast']['stream_url']);
                                                 else
                                                 echo "rtmp://".$ip.":1935/vod/".$broadcast['broadcast']['filename'];?>"
                                                },{
                                                    file:"<?php  if($broadcast['status'] == "online")
                                                 echo str_replace("rtsp","http",$broadcast['broadcast']['stream_url']);
                                                 else
                                                 echo "http://".$ip.":1935/vod/".$broadcast['broadcast']['filename'];?>"
                                                }],
                                                height: 380,
                                                width: "100%",
                                                image: '<?php echo $broadcast['broadcast']['broadcast_image'];?>',
                                            });
                                        </script>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" id="close-btn" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Model End-->

                    </div>
                    <?php } ?>
                    <!--Reported Broadcost listing End-->
                    <!--Pagination start-->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="report-bc-pagination">
                            <nav>
                               {{ $reported_broadcasts->links() }}
                            </nav>
                        </div>
                    </div>
                    <!--Pagination End-->
                 
                </div>
            </div>
            <!--Right Content Area End-->
   
@endsection

@push('admin-script')
<?php
    if(isset($_GET['approved'])) {?>
        <script>
            alert('Broadcast is approved successfully.')
        </script>
<?php } else if(isset($_GET['delete'])) {?>
    <script>
        alert('Broadcast is deleted successfully.')
    </script>
<?php }?>
<script>
    $(document).ready(function(){
        $('#close-btn').click(function(){
            close_broadcast();
        });
        $('#model-cross').click(function(){
            close_broadcast();
        });
        $('#close-modal').click(function(){
            close_broadcast();
        });
    });
</script>
@endpush