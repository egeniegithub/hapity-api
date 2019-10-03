var baseurl='';
if(window.location.host=='localhost')
    baseurl = 'http://localhost/hapity/web_2.0/';
else
    baseurl = 'https://www.hapity.com/';
$('.login-submit').click(function(e){

    var name =$('.user').val().trim();
    var pass =$('.pass').val().trim();

    if(name!=''&&pass!=''){
        $.ajax({
            type: 'POST',
            url: baseurl+'Admin/verify',
            data: {
                name:name,pass:pass,
            },
            success: function(data){
                //console.log(data);
                if(data>0){
                    //redirect
                    location.assign(baseurl+'Admin/dashboard');
                    //console.log('login Accepted');
                }
                else{
                    //stay
                    alertify.alert('Invalid username or password.');
                   // console.log('login not Accepted');
                    //location.assign(baseurl+'Admin/');
                }
            }
        });
    }else{
        if(name=='' && pass==''){
            //alertify.set('notifier','position', 'bottom-right');
            alertify.alert('Error','Please enter both fields');
        }
        else if(name==''){
            alertify.alert('Error','Please Fill the name field');
        }
        else{
            alertify.alert('Error','Please Fill the password field');
        }

    }
    return false;
});
$( document ).ready(function() {
    $('#reset-password').click(function(){
        //console.log("asdf");
        var old_pass =$('.old-pass').val().trim();
        var new_pass =$('.new-pass').val().trim();
        if(old_pass!=''&&new_pass!=''){
            $.ajax({
                type: 'POST',
                url: baseurl+'admin/reset_password',
                data: {
                    old_pass:old_pass,new_pass:new_pass,
                },
                success: function(data){
                    console.log(data);
                    if(data=='same'){
                        alertify.alert('New password should be different')
                    }
                    else if(data=='incorrect'){
                        alertify.alert('Your old Password is incorrect');
                    }
                    else if(data=='success'){
                        //location.assign(baseurl+'admin/settings');
                        alertify.alert('Your Password has been changed successfully');
                    }
                    else{
                        alertify.alert('Something wrong is happened');
                    }
                }
             });
        }
        else{
            if(old_pass==''&&new_pass==''){
                alertify.error('Please Fill the fields');
            }
            else if(old_pass==''){
                alertify.error('Please Fill the old password');
            }
            else{
                alertify.error('Please Fill the new password');
            }
        }
        return false;
    });
});

function del_broadcast_report(id){
    var answer=confirm('Do you really want to delete it?');
    if(answer){
        //alert(id);
        location.assign(baseurl+'admin/delete_broadcast/'+id);
    }
}
function del_user_report(id){
    var answer=confirm('Do you really want to delete it?');
    if(answer){
        //alert(id);
        location.assign(baseurl+'admin/delete_user/'+id);
    }
}
function del_a_report(id){
    var answer=confirm('Do you really want to delete it?');
    if(answer){
        //alert(id);
        location.assign(baseurl+'admin/delete_a_broadcast/'+id);
    }
}
function del_a_user(id){
    var answer=confirm('Do you really want to delete it?');
    if(answer){
        //alert(id);
        location.assign(baseurl+'admin/delete_a_user/'+id);
    }
}
function close_broadcast(){
    $('.jwplayer').each(function(){
        var id=$(this).attr('id');
        // jwplayer(id).stop();
    });
}

jQuery(document).on('click', '.close-video', function(event) {
    event.preventDefault();
    $('.jwplayer').each(function(){
        var id=$(this).attr('id');
        jwplayer(id).stop();
    });
});