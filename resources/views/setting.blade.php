@php 

@endphp

@extends('layouts.app')

@push('css')
 	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/crop.css')}}"/>
	<link href="{{asset('assets/css')}}/jquery.loader.css" rel="stylesheet" />
@endpush
@section('content')


    <div class="profile-page">

        <div class="container">
            <div class="section-main">
          
            <div class="account_setting">

                <div class="account-seetings-new">
                    <div class="row">
                        <div class="col-md-5 col-md-offset-4">
                            <div class="setting_title text-center">
                                <h1>Account Settings</h1>
                                <span>Change your basic account settings</span>
                            </div>
                            <form class="" method="post" actipon="{{url('save_setting')}}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" id="user_id" value="{{ auth::user()->id }}">
                            <div class="account-settigs-content">
                                <div class="form-group text-center">
                                    <figure>
                                    	@if(isset(auth::user()->profile->profile_picture ) && !empty(auth::user()->profile->profile_picture ))
                                        	<img src='{{ auth::user()->profile->profile_picture }}' class='profile_picture'>
                                        @else
                                        	<img src='{{asset('assets/images/null.png')}}' class='profile_picture'>
                                        @endif
                                    </figure>
                                    <h2 class="username-title">{{ auth::user()->username }}</h2>
                                    <?php //if($userinfo->login_type == 'simple'){?>
                                                <div class="container-box">
                                                    <div class="imageBox">
                                                        <div class="thumbBox"></div>
                                                        <div class="spinner" style="display: none">Loading...</div>
                                                    </div>
                                                    <div class="clearfix"></div> 
                                                    <input type="button" id="btnZoomIn" value="+" style="float: left " class='controls'>
                                                        <input type="button" id="btnZoomOut" value="-" style="float: left" class='controls'>
                                                    <div class="clearfix"></div> 
                                                    <div class="action action-width">
                                                        <?php /*<input type="file" id="file" style="float:left; width: 250px" accept="image/gif, image/jpeg, image/png "> */ ?> 
                                                        <div style="position:relative;">
                                                        <a class='btn-purple' href='javascript:;'>
                                                            Change profile picture
                                                            <input type="file" name="image" id="file" value="Image" accept="image/gif, image/jpeg, image/png" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;'/>
                                                        </a>
                                                        </div>
                                                    </div>
                                                    <input type='hidden' id='profile_picture' value="{{ auth::user()->profile->profile_picture }}">
                                                    <input type='hidden' id='login-type' value="<?php // echo $userinfo->login_type;?>">
                                                    <input type="hidden" id='user_id' name="user_id" value="{{ auth::user()->profile->user_id }}">
                                                </div>
                                    <?php //}?>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <label>Username</label>
                                    <div class="field-names">
                                        <input class="input-s" type="text" id='username' value="{{ auth::user()->username }}">
                                        <img style="display:none;" src="{{ asset('/assets/images/tick.png') }}" />
                                    </div>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <label>Email</label>
                                    <div class="field-names">
                                        <input class="input-s" type="text" id='email' value='{{ auth::user()->email }}'>
                                    </div>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <label>Plugin ID</label>
                                    <div class="field-names">
                                        <input class="input-s" type="text" value='{{ auth::user()->profile->auth_key }}' readonly>
                                    </div>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <div class="field-names">
                                        <div class="row">
                                                <div class="col-sm-9 col-sm-offset-2">
                                                    <!-- <input class="pull-left cehckbox-s" type="checkbox" id='is_sensitive' value="" name="is_sensitive" <?php //if($userinfo->is_sensitive == 'yes'){ echo 'checked'; } ?>> 
                                                    <label class="pull-left">My broadcasts contain sensitive information</label> -->

                                                    <div class="styled-input-single">
                                                        <input type="checkbox" id='is_sensitive' value="" name="is_sensitive" <?php // if($userinfo->is_sensitive == 'yes'){ echo 'checked'; } ?>/>
                                                        <label for="is_sensitive">My broadcasts contain sensitive information</label>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <input type="submit" class="save-btn" value="Save" id='account-save'>
                                </div><!-- form group -->
                            </div>
                        </form><!-- form ends here -->
                        </div>

                    </div>
                </div>

                <div class="setting_holder">
                    <div class="form_holder">
      
                    </div>
                    <?php // if($userinfo->login_type=='simple'){?>
                    <div class="form_holder hide">
                        <div class="setting_title">
                            <h1>Privacy Settings</h1>
                            <span>Change your password or apply privacy on your broadcasts</span>
                        </div>
                        <form class="account_form">
                            <div class="fieldset">
                                <div class="fields-wrap">
                                   <ul>
                                        <li>
                                            <label>Current Password</label>
                                            <div class="col_field">
                                                <div class="col_field1">
                                                    <input type="password" class="" id='current-password'>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <label>New Password</label>
                                            <div class="col_field">
                                                <div class="col_field1">
                                                    <input type="password" class="" id='new-password'>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <label>Confirm Password</label>
                                            <div class="col_field" id='{{ auth::user()->id }}'>
                                                <div class="col_field1">
                                                    <input type="password" class="" id='confirm-password'>
                                                </div>
                                                <input type="submit" class="save_form" value="Save" id='privacy-save' >
                                            </div>
                                        </li>
                                    </ul> 
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php // } ?>
                </div>
            </div>
        </div>
        </div>

    </div>
    <div class="clear"></div>


@endsection

@push('script')
<script type="text/javascript">
$('#account-save').click(function(){
    
       $.loader({className:"blue", content:"<i class='fa fa-refresh fa-spin fa-3x fa-fw margin-bottom loadingclass'></i>"});
        
       email = $('#email').val().trim();
       username = $('#username').val().trim();
       user_id = $('#user_id').val().trim();
       // alert($(this).parent());
       logintype= $('#login-type').val().trim();
       var profile_picture = '';

        if($('#is_sensitive').is(':checked')){
            is_sensitive = 'yes';
        } else {
            is_sensitive = 'no';
        }
       if(picture_change=='true')
           profile_picture = cropper.getDataURL();
       else if(picture_change=='false')
           profile_picture = $('#profile_picture').val();
       
       if(username==''){
           $.loader('close');
            alertify.log('Please enter username.');            
        }
       else if(email==''){
           $.loader('close');
            alertify.log('Please enter email address.');            
        }
       else if(!validateEmail(email)){
           $.loader('close');
            alertify.alert('Invalid email address, please enter correct email.');
            error = 'true';            
       }
       else{
        $.ajax({
                type: 'GET',
                url: baseurl+'webservice/is_user_username/',
                data: {
                    username:username,
                    user_id:user_id
                },
                success: function(msg){
                    if(msg=='true'){
                        $.loader('close');
                         alertify.alert('Username already exist, please choose different username.');
                         
                    }
                    else{
                        $.ajax({
                            type: 'GET',
                            url: baseurl+'webservice/is_user_email/',
                            data: {
                                email:email,
                                user_id:user_id
                            },
                            success: function(msg){
                                
                                if(msg=='true'){
                                    $.loader('close');
                                    alertify.alert('Email already exist, please choose different email.');
                                    
                                }
                                else{
                                    if(logintype=='simple'){
                                        // alert(email);
                                        // alert(user_id);
                                        // alert(username);
                                        // alert(profile_picture);
                                        // alert(picture_change);
                                        // alert(is_sensitive);
                                        $.ajax({
                                            type: 'POST',
                                            url: baseurl+'main/save_settings/',
                                            data: {
                                                email:email,
                                                user_id:user_id,
                                                username:username,
                                                profile_picture:profile_picture,
                                                type:'account',
                                                picture_change:picture_change,
                                                is_sensitive:is_sensitive
                                            },
                                            success: function(msg){
                                                if(msg=='success'){
                                                    $.loader('close');
                                                    alertify.alert('Account settings have been successfully changed..');
                                                    var url = baseurl+"profile/"+username;
                                                    var html = '<a href="'+url+'">'+url+'</a>'
                                                    $('.url').html(html);
                                                }
                                                location.reload();
                                            }
                                        });

                                    }
                                    else{

                                        $.ajax({
                                            type: 'POST',
                                            url: baseurl+'main/save_settings/',
                                            data: {
                                                email:email,
                                                user_id:user_id,
                                                username:username,
                                                type:'facebook'
                                            },
                                            success: function(msg){

                                                if(msg=='success'){
                                                    $.loader('close');
                                                    alertify.alert('Account settings have been successfully changed.');
                                                    var url = baseurl+"profile/"+username;
                                                    var html = '<a href="'+url+'">'+url+'</a>'
                                                    $('.url').html(html);
                                                }

                                                location.reload();

                                            }
                                        });
                                    }
                                }

                            }
                        });

                    }
                }
            });
        }
        return false;
}) ;
    </script>
@endpush