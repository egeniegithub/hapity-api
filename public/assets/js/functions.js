/*
 SelectNav.js (v. 0.1)
 Converts your <ul>/<ol> navigation into a dropdown list for small screens
 https://github.com/lukaszfiszer/selectnav.js
 */
/*function resizeContent() {
    $height = $(window).height() - 110130;
    $('.live-streaming, .create-content-wrap').height($height);
}*/

jQuery(document).ready(function($) {
    var window_height = $(window).height();

    $('.live-streaming, .create-content-wrap').css('min-height', window_height);
});
// $(window).resize(function (){
//         var window_height = $(window).innerheight();

//     $('.live-streaming').css('min-height', window_height);
//     });
window.selectnav = function () {
    return function (p, q) {
        var a, h = function (b) {
            var c;
            b || (b = window.event);
            b.target ? c = b.target : b.srcElement && (c = b.srcElement);
            3 === c.nodeType && (c = c.parentNode);
            c.value && (window.location.href = c.value)
        }, k = function (b) {
            b = b.nodeName.toLowerCase();
            return"ul" === b || "ol" === b
        }, l = function (b) {
            for (var c = 1; document.getElementById("selectnav" + c); c++)
                ;
            return b ? "selectnav" + c : "selectnav" + (c - 1)
        }, n = function (b) {
            g++;
            var c = b.children.length, a = "", d = "", f = g - 1;
            if (c) {
                if (f) {
                    for (; f--; )
                        d += r;
                    d += " "
                }
                for (f = 0; f <
                c; f++) {
                    var e = b.children[f].children[0];
                    if ("undefined" !== typeof e) {
                        var h = e.innerText || e.textContent, i = "";
                        j && (i = -1 !== e.className.search(j) || -1 !== e.parentElement.className.search(j) ? m : "");
                        s && !i && (i = e.href === document.URL ? m : "");
                        a += '<option value="' + e.href + '" ' + i + ">" + d + h + "</option>";
                        t && (e = b.children[f].children[1]) && k(e) && (a += n(e))
                    }
                }
                1 === g && o && (a = '<option value="">' + o + "</option>" + a);
                1 === g && (a = '<select class="selectnav" id="' + l(!0) + '">' + a + "</select>");
                g--;
                return a
            }
        };
        if ((a = document.getElementById(p)) && k(a)) {
            document.documentElement.className +=
                " js";
            var d = q || {}, j = d.activeclass || "active", s = "boolean" === typeof d.autoselect ? d.autoselect : !0, t = "boolean" === typeof d.nested ? d.nested : !0, r = d.indent || "\u2192", o = d.label || "- Navigation -", g = 0, m = " selected ";
            a.insertAdjacentHTML("afterend", n(a));
            a = document.getElementById(l());
            a.addEventListener && a.addEventListener("change", h);
            a.attachEvent && a.attachEvent("onchange", h)
        }
    }
}();

$(document).ready(function () {
    selectnav('menus', {
        nested: true,
        indent: '-'
    });
    /*$(window).resize(function (){
        $('.stream-conatiner').css({
            position:'absolute',
            left: ($(window).width() - $('.stream-conatiner').outerWidth())/2,
            top: ($(window).height() - $('.stream-conatiner').outerHeight())/2
        });
    });
    $(window).resize();*/

    /*$('#start-streaming').click(function(){
        $(this).parents().find('#stream-conatiner-wrp').fadeOut('fast');
        $(this).parents().find('#stream-stop-conatiner-wrp').fadeIn('fast');
    });*/

    $('#stop-streaming').click(function(){
        $(this).parents().find('#stream-conatiner-wrp').fadeIn('fast');
        $(this).parents().find('#stream-stop-conatiner-wrp').fadeOut('fast');
    });

    /*resizeContent();

    $(window).resize(function() {
        resizeContent();
    });*/

    $("#selectnav1").children().first().remove();
    $("#selectnav1").children().first().before("<option selected='selected' onclick='home()'  value="+ baseurl +" >HOME</option>");

    $(".tab_content").hide();
    $(".tab_content:first").show();

    $("ul.tabs li").click(function () {
        $("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $(".tab_content").hide();
        var activeTab = $(this).attr("rel");
        $("#" + activeTab).fadeIn();
    });

    $("#nextLink").children().first().addClass("next");

});

function home(){
    window.location.assign(baseurl + "main");
}


$(function () {

    var appendthis = ("<div class='modal-overlay js-modal-close' onclick='closepopup();'></div>");

    $('a[data-modal-id]').click(function (e) {
        e.preventDefault();
        $("body").append(appendthis);
        $(".modal-overlay").fadeTo(500, 0.7);
        //$(".js-modalbox").fadeIn(500);
        var modalBox = $(this).attr('data-modal-id');
        $('#' + modalBox).fadeIn($(this).data());

        $("body").css("overflow-y", "hidden");
    });


    $(".js-modal-close, .modal-overlay").click(function () {
        $(".modal-box_popup, .modal-overlay").fadeOut(500, function () {
            $("body").css("overflow-y", "scroll");
            $(".modal-overlay").remove();
        });

    });

    $(window).resize(function () {
        $(".modal-box_popup").css({
            top: ($(window).height() - $(".modal-box_popup").outerHeight()) / 2,
            left: ($(window).width() - $(".modal-box_popup").outerWidth()) / 2
        });
    });

    $(window).resize();





});



function closepopup()
{
    $(".modal-box_popup, .modal-overlay").fadeOut(500, function () {
        $("body").css("overflow-y", "scroll");
        $(".modal-overlay").remove();
    });
}


function searchbar(){
    $('div.search_data').addClass('show_result');
}


function removesearch(){
    $('div.search_data').removeClass('show_result');
}
