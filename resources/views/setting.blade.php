@php 

@endphp

@extends('layouts.app')

@push('css')
 	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/crop.css')}}"/>
	<link href="{{asset('assets/css')}}/jquery.loader.css" rel="stylesheet" />

    <style type="text/css">
        .section-main {
            float: left;
            width: 100%;
            padding: 0px 20px !important;
            margin: 0px 0 0 0 !important;
        }
        form.account_form {
            padding: 30px 0 5px 10px;
             width: 100%; 
        }
        .text-error{
            color: red;
        }
        .setting-page{
            margin:0px auto !important;
            float: none;
        }
    </style>
@endpush
@section('content')


    <div class="profile-page">

        <div class="container">
            <div class="section-main">
          
            <div class="account_setting">

                <div class="account-seetings-new">
                    <div class="row">
                        <div class="col-sm-12">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block text-center">
                                    <button type="button" class="close" data-dismiss="alert">×</button> 
                                        <strong>{{ $message }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 setting-page">
                            <div class="setting_title text-center">
                                <h1>Account Settings</h1>
                                <span>Change your basic account settings</span>
                            </div>
                            <form class="full-width"  enctype="multipart/form-data">
                                {{-- method="post" --}} {{-- action="{{route('settgins.save')}}" --}}
                                @csrf
                            <div class="account-settigs-content">
                                <div class="form-group text-center">
                                    <figure>
                                        @php 
                                        $thumbnail_image = auth::user()->profile->profile_picture;
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
                                            <img src="{{ $thumbnail_image }}" alt="" />
                                        @else
                                            <img src="{{ asset('images/profile_pictures' . '/'  .$thumbnail_image) }}" alt="" />
                                        @endif
                                        
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
                                                    <input type='hidden' id='profile_picture' name="profile_picture" value="{{ auth::user()->profile->profile_picture }}">
                                                    <input type='hidden' id='login-type' name="login_type" value="<?php // echo $userinfo->login_type;?>">
                                                    <input type="hidden" id='user_id' name="user_id" value="{{ auth::user()->profile->user_id }}">
                                                </div>
                                    <?php //}?>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <label>Username</label>
                                    <div class="field-names">
                                        <input class="input-s" type="text" id='username' name="username" value="{{ auth::user()->username }}" required readonly="">
                                        @if ($errors->has('username'))
                                            <div class="error alert-danger">{{ $errors->first('username') }}</div>
                                        @endif
                                        <img style="display:none;" src="{{ asset('/assets/images/tick.png') }}" />
                                    </div>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <label>Email</label>
                                    <div class="field-names">
                                        <input class="input-s" type="text" id='email' name="email" value='{{ auth::user()->email }}' required readonly="">
                                        @if ($errors->has('email'))
                                            <div class="error alert-danger">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <label>Plugin ID</label>
                                    <div class="field-names">
                                        <input class="input-s" type="text" name="auth_key" value='{{ auth::user()->profile->auth_key }}' readonly>
                                    </div>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <div class="field-names">
                                        <div class="row">
                                                <div class="col-sm-9 col-sm-offset-2">
                                                    <div class="styled-input-single">
                                                        <input type="checkbox" id='is_sensitive' value="{{ auth::user()->profile->is_sensitive }}" name="is_sensitive" @if(auth::user()->profile->is_sensitive == 1) checked="" @endif />
                                                        <label for="is_sensitive">My broadcasts contain sensitive information</label>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <input type="submit" class="save-btn" value="Save" id='account-save' >
                                </div><!-- form group -->
                            </div>
                            </form>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="account-settigs-content">
                                <div class="setting_title text-center">
                                    <h1>Change your password</h1>
                                    <span>Change your account Login Credentials</span>
                                </div>
                                
                                <form class="account_form " method="post" action="{{ route('reset.password') }}">
                                    @csrf
                                    <figure class="text-center lock-image " >
                                        <img src='{{asset('assets')}}/images/lockgreen.png' class='profile_picture'>
                                    </figure>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            @if(Session::has('flash_message'))
                                                <div class="alert @if(Session::has('flash_message')) alert-success @endif ">
                                                    @if(Session::has('flash_message')) {{ Session::get('flash_message') }} @endif
                                                </div>
                                            @else
                                                <div class="alert @if(Session::has('flash_message_delete')) alert-danger @endif ">
                                                    @if(Session::has('flash_message_delete')) {{ Session::get('flash_message_delete') }} @endif 
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="fieldset change-credentials-form">
                                        <div class="fields-wrap">
                                            <div class="form-group text-center field-data">
                                                <label>Current Password</label>
                                                <div class="field-names">
                                                    <input class="input-s"  type="password" class="" id='current-password' name="current_password" placeholder="Enter Current Password " required="">
                                                    @if ($errors->has('current_password'))
                                                        <div class="error alert-danger">{{ $errors->first('current_password') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group text-center field-data">
                                                <label>New Password</label>
                                                <div class="field-names">
                                                    <input class="input-s"  type="password" class="" id='new-password' name="new_password" placeholder="Enter New Password" required="">
                                                    @if ($errors->has('new_password'))
                                                        <div class="error alert-danger">{{ $errors->first('new_password') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group text-center field-data">
                                                <label>Confirm Password</label>
                                                <div class="field-names">
                                                    <input class="input-s"  type="password" class="" id='confirm-password' name="confirm_password" placeholder="Enter Confirm Password" required="">
                                                    @if ($errors->has('confirm_password'))
                                                        <div class="error alert-danger">{{ $errors->first('confirm_password') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group text-center field-data">
                                                <input type="submit" class="save-btn" value="Update Password" >
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div> --}}
                    </div>
                </div>
         
                
            </div>
        </div>
        </div>

    </div>
    <div class="clear"></div>


@endsection

@push('script')
<script type="text/javascript">
    $("#is_sensitive").click(function() {
        if($("#is_sensitive").val()=='0'){
         $("#is_sensitive").val('1');
        }else{
         $("#is_sensitive").val('0');
        }    
    });

$('#account-save').click(function(e){
        e.preventDefault();
        $.loader({className:"blue", content:"<i class='fa fa-refresh fa-spin fa-3x fa-fw margin-bottom loadingclass'></i>"});
        
       email = $('#email').val().trim();
       username = $('#username').val().trim();
       user_id = $('#user_id').val().trim();
       logintype= $('#login-type').val().trim();
       var profile_picture = '';

        if($('#is_sensitive').is(':checked')){
            is_sensitive = 1;
        } else {
            is_sensitive = 0;
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
                url: "{{route('check_username')}}",
                data: {
                    username:username,
                    user_id:user_id
                },
                success: function(msg){
                    if(msg=='true'){
                        $.loader('close');
                         alertify.alert('Username already exist, please choose different username.');
                    }
                    // else{
                        $.ajax({
                            type: 'GET',
                            url: "{{route('check_email')}}",
                            data: {
                                email:email,
                                user_id:user_id
                            },
                            success: function(msg){
                                
                                if(msg=='true'){
                                    $.loader('close');
                                    alertify.alert('Email already exist, please choose different email.');
                                }
                                // else{

                                        $.ajax({
                                            type: 'POST',
                                            url: "{{route('settgins.save')}}",
                                            data: {
                                                email:email,
                                                user_id:user_id,
                                                username:username,
                                                profile_picture:profile_picture,
                                                type:'account',
                                                picture_change:picture_change,
                                                is_sensitive:is_sensitive,
                                                _token: "{{ csrf_token() }}",
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

                                // }

                            }
                        });

                    // }
                }
            });
        }
        return false;
}) ;



    </script>
@endpush