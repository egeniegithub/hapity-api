@extends('layouts.app')

@push('css')

@endpush
@section('content')

@php
    $ipArr = array(0 => '52.18.33.132', 1 => '52.17.132.36');
    $index = rand(0,1);
    $ip = $ipArr[$index];
@endphp

<style type="text/css" media="screen">
    .my-bordcast-single iframe {
        width: 100%;
        border:0;
        height: 600px;
    }
</style>
<div class="section-main detail-broadcast" style="padding-top: 50px;">
    <div class="container">
        <div class="flash-error col-xs-12 col-sm-12 col-md-12" style="display: none;">Flash player is not supported by your browser, you need flash installed to see Broadcast Videos</div>
        
        <div class="col-xs-12 col-sm-3 col-md-3"></div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="my-bordcast-single bordcast-active">
                <iframe src="https://api.hapity.com/widget.php?stream=rtmp://52.17.132.36:1935/vod/<?php echo $filename; ?>&title=<?php echo $broadcast['title'];?>&status=offline&bid=1308&broadcast_image=<?php echo $broadcast['broadcast_image'];?>"></iframe>

                <?php /*div href="#" class="bordcast-play image-section">
                    <div class="broadcast-streaming" id="broadcast-<?php echo $broadcast['id'];?>">Loading Broadcast</div>
                </div>
                <script type="text/javascript">
                    jwplayer("broadcast-<?php echo $broadcast['id'];?>").setup({
                        sources: [{
                            file: "<?php  if($broadcast['status'] == "online")
                         echo str_replace("rtsp","rtmp",$broadcast['stream_url']);
                         else
                         echo "rtmp://".$ip.":1935/vod/".$filename;?>"
                        },{
                            file:"<?php  if($broadcast['status'] == "online")
                         echo str_replace(array("rtsp", "rtmp"),"http",$broadcast['stream_url']."/playlist.m3u8");
                         else
                         echo "http://".$ip.":1935/vod/".$filename."/playlist.m3u8";?>"
                        }],
                    playButton: 'https://www.hapity.com/images/play.png',
                    height: 380,
                    width: "100%",
                    image: '<?php echo $broadcast['broadcast_image'];?>',
                    skin: 'stormtrooper',
                    primary: 'flash'
                    });
                    $(document).ready(function(){
                        $(".jw-reset").click(function(){
                            jwplayer("broadcast-<?php echo $broadcast["id"];?>").play('play');
                        });
                    });
                </script> */ ?>
                <?php if($broadcast['status'] == "online") : ?>
                    <span class="broadcast-live"></span>
                <?php else : ?>
                    <span class="broadcast-offline"></span>
                <?php endif; ?>
                <h3 class="my-bordcast-title"><?php echo $broadcast['title'];; ?></h3>
                <p class="description"><?php echo $broadcast['description']; ?></p>

                <ul class="post-options clearfix share-with-icons-live">
                    <li class="username">
                        <img src="{{ auth::user()->profile->profile_picture }}">
                        &nbsp; <span>{{ auth::user()->username }}</span>
                    </li>
                    <li><a href="javascript:;" data-modal-id="embed-code-popup-<?php echo $broadcast['id'];?>" class="code-icon"><i class="fa fa-code"></i></a></li>
                    <li class="twitter-icon"><a href="https://twitter.com/home?status=<?php echo $broadcast['share_url'] ?>" target="_blank" class="twitter"><i class="fa fa-twitter"></i></a></li>
                    <li class="facebook-icon"><a href="javascript:void(0)" onclick="fbshare('fbtest','<?php echo $broadcast['stream_url'];?>','<?php echo $broadcast['broadcast_image'];?>')"><i class="fa fa-facebook"></i></a></li>                           
                </ul>
                <div id="embed-code-popup-<?php echo $broadcast['id'];?>" class="modal-box_popup">
                    <header> <a href="javascript:;" class="js-modal-close close">×</a>
                        <h3>Copy & Paste below code in your website</h3>
                    </header>
                    <div class="modal-body">
                        <div class="embedcode-modal-innser">
                            <textarea readonly=""><iframe height="600" width="100%" scrolling="no" frameborder="0" 
                                src="https://api.hapity.com/widget.php?stream=<?php echo $broadcast['stream_url'];?>&title=<?php echo urlencode($broadcast['title']);?>&status=<?php echo $broadcast['status'];?>&broadcast_image=<?php echo $broadcast['broadcast_image'];?>">
                                </iframe></textarea>                        
                        </div>
                    </div>
                </div>
                <div class="social-like-btn">
                    <!-- <a href="javascript:void(0)" class="like-button-<?php echo $broadcast['id'];?>">
                        <?php //if($broadcast['liked']!=true){ ?>
                            <img src="<?php //echo base_url('assets/'); ?>/images/icon-like1.png" onClick="like_broadcast('<?php // echo $user_id;?>','<?php echo $broadcast['id'];?>')"> 
                        <?php //}else{
                                ?>
                             <img src="<?php //echo base_url('assets/'); ?>/images/icon-unlike.png" onClick="unlike_broadcast('<?php // echo $user_id;?>','<?php echo $broadcast['id'];?>')">
                        <?php
                       //}                                            
                        ?>
                    </a> -->
                    
                </div>
                <!-- <div class="comment-respond">
                    <ul>
                        <ul class="comment-<?php // echo $broadcast['id'];?>">
                            <li>
                                <figure><img src="<?php //echo $userdata->profile_picture; ?>" alt="#"></figure>
                                <div class="text" id='<?php // echo $user_id;?>'>
                                    <input type="text" class='new-comment' id='<?php echo $broadcast['id'];?>' placeholder="Post your comment..."/>
                                </div>
                            </li>
                            <?php // foreach($comments as $comment){?>
                            <li>
                                <figure><a href="javascript:void(0)"><img src="<?php // echo $comment['profile_picture'] ;?>" alt="#"></a></figure>
                                <div class="text">
                                    <h2><a href="javascript:void(0)"><?php // echo $comment['username'] ;?></a><time><?php // echo $comment['date'] ;?> </time></h2>
                                    <p><?php // echo $comment['comment'] ;?></p>
                                </div>
                            </li>
                            <?php // } ?>
                
                        </ul>
                    </ul>
                </div> -->

            </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3"></div>
    </div>

</div>

@endsection

@push('script')

<script type="text/javascript">
    
    $(document).ready(function(){
        var channel = pusher.subscribe('comments-<?php echo $broadcast['id'];?>');
        channel.bind('new_comment', displayComment);
    });

</script>

@endpush