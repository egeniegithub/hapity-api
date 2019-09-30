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
                            <form class="">
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
                        <!-- <form class="account_form ">
                            <div class="fieldset">
                                <label>Profile Picture</label>
                                <div class="fields-wrap">
                                    <ul>
                                        <li>
                                            <div class="col_field">
                                                <figure>
                                                    <img src='<?php // echo $userinfo->profile_picture;?>' class='profile_picture'>
                                                </figure>
                                                <?php // if($userinfo->login_type == 'simple'){?>
                                                <div class="container-box">
                                                    <div class="imageBox">
                                                        <div class="thumbBox"></div>
                                                        <div class="spinner" style="display: none">Loading...</div>
                                                    </div>
                                                    <div class="clearfix"></div> 
                                                    <input type="button" id="btnZoomIn" value="+" style="float: left " class='controls'>
                                                        <input type="button" id="btnZoomOut" value="-" style="float: left" class='controls'>
                                                    <div class="clearfix"></div> 
                                                    <div class="action">
                                                        <?php /*<input type="file" id="file" style="float:left; width: 250px" accept="image/gif, image/jpeg, image/png "> */ ?> 
                                                        <div style="position:relative;">
                                                        <a class='btn btn-primary' href='javascript:;'>
                                                            Change Profile Photo
                                                            <input type="file" name="image" id="file" value="Image" accept="image/gif, image/jpeg, image/png" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;'/>
                                                        </a>
                                                        </div>
                                                    </div>
                                                    <input type='hidden' id='profile_picture' value="<?php // echo $userinfo->profile_picture;?>">
                                                    <input type='hidden' id='login-type' value="<?php // echo $userinfo->login_type;?>">
                                                </div>
                                                <?php// }?>
                                            </div> 
                                        </li>
                                        <li>
                                        <label>Username</label>
                                        <div class="col_field">                                        
                                            <div class="col_field1">
                                                <input type="text" id='username' value="<?php // echo $userinfo->username;?>">
                                                <img style="display:none;" src="<?php // echo site_url(); ?>/assets/images/tick.png" />
                                            </div>
                                            <?php /* <div class="url"><a href="--><?php //echo site_url('/profile'); ?><!--/--><?php //echo $userinfo->username;?><!--">--><?php //echo site_url('/profile'); ?><!--/--><?php //echo $userinfo->username;?><!--</a></div> */ ?>
                                        </div>
                                        </li>
                                        <li>
                                        <label>Email</label>
                                        <div class="col_field" >                                        
                                            <div class="col_field1">
                                                <input type="text" id='email' value='<?php // echo $userinfo->email;?>'>
                                            </div>
                                        </div>
                                        </li>
                                        <li>
                                            <label>Your Plugin's ID</label>
                                            <div class="col_field" >
                                                <div class="col_field1">
                                                    <input type="text" value='<?php // echo $userinfo->auth_key;?>' readonly>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <label>My Broadcast(s) contains sensitive media</label>
                                            <div class="col_field" >
                                                <div class="col_field1">
                                                    <input type="checkbox" id='is_sensitive' value="" name="is_sensitive" <?php // if($userinfo->is_sensitive == 'yes'){ echo 'checked'; } ?>>
                                                </div>
                                            </div>
                                        </li>

                                        <li id='<?php // echo $user_id;?>'>
                                            <input type="submit" class="save_form" value="Save" id='account-save'>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form> -->
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

@endpush