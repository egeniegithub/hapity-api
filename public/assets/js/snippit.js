$(document).ready(function(){
    var id = $("#uid").html().trim();
    $.ajax( {
        type:'Get',
        dataType: "jsonp",
        url:'http://api.hapity.com/webservice/get_last_broadcast/user_id/'+id,
        success:function(data) {
        var status = data.status;
        if(status =='success'){
            var URL = data.url;
            var title = data.title;
            var broadcast_status = data.broadcast_status;
            var broadcast_image = data.broadcast_image;
            var broadcast_url = URL;
            broadcast_url = broadcast_url.replace("rtsp","rtmp");
            if(broadcast_status == "offline"){
                broadcast_url = broadcast_url.replace("live","vod");
            }
            jwplayer("stream").setup({
                sources: [{
                    file: broadcast_url
                },{
                    file:broadcast_url+"/playlist.m3u8"
                }],
                height: 380,
                width: "100%",
                image: broadcast_image,
            });
//            console.log(data);
        }
        else{
            $("#stream").html("<h2>No broadcast found.<h2>");
        }
        }
    });
});
