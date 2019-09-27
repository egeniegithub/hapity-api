

var scroll = 0;
var page1 = 2;
var page2 = 2;
p1 = false;
p2 = false;

$(document).ready(function(){

    $('#pagination').hide();
    $('.page').hide();

    $(window).scroll(function (event) {
        if($(window).scrollTop() + $(window).height() == $(document).height()) {
           //bottom
            if($("#tab1").is(":visible")){
                tab = "#tab1";
                mUrl = baseurl + "main/index/" + page1;
                page1 += 2;
                p1 = true;
                p2 = false;
            }
            else{
                tab = "#tab2";
                mUrl = baseurl + "main/index/" + page2;
                page2 += 2;
                p1 = false;
                p2 = true;
            }


            $.ajax({
                url: mUrl
            }).done(function(data){
                 broad = $(data).find(tab);
                 broad = $(broad).find(".tab_content-inner");
                if(broad.html() == "" || broad.html() == null){
                    if(p1)
                        page1 -= 2;
                    else
                        page2 -= 2;
                }
                 $(tab).append(broad);
                 $('#pagination').hide();
            });
        }
    });

});


