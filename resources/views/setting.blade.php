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
                        <div class="col-sm-12">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block text-center">
                                    <button type="button" class="close" data-dismiss="alert">×</button> 
                                        <strong>{{ $message }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-5 col-md-offset-4">
                            <div class="setting_title text-center">
                                <h1>Account Settings</h1>
                                <span>Change your basic account settings</span>
                            </div>
                            <form class="" method="post" action="{{route('settgins.save')}}" enctype="multipart/form-data">
                                @csrf
                            <div class="account-settigs-content">
                                <div class="form-group text-center">
                                    <figure>
                                    	@if(isset(auth::user()->profile->profile_picture ) && !empty(auth::user()->profile->profile_picture ))
                                        	<img src='{{ asset('images/profile_pictures/'.auth::user()->profile->profile_picture) }}' class='profile_picture'>
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
                                        <input class="input-s" type="text" id='username' name="username" value="{{ auth::user()->username }}" required>
                                        @if ($errors->has('username'))
                                            <div class="error alert-danger">{{ $errors->first('username') }}</div>
                                        @endif
                                        <img style="display:none;" src="{{ asset('/assets/images/tick.png') }}" />
                                    </div>
                                </div><!-- form group -->
                                <div class="form-group text-center field-data">
                                    <label>Email</label>
                                    <div class="field-names">
                                        <input class="input-s" type="text" id='email' name="email" value='{{ auth::user()->email }}' required>
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
                                    <input type="submit" class="save-btn" value="Save" {{-- id='account-save' --}} >
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


$('#account-save').click(function(){
    
       $.loader({className:"blue", content:"<i class='fa fa-refresh fa-spin fa-3x fa-fw margin-bottom loadingclass'></i>"});
        
   
}) ;
    </script>
@endpush