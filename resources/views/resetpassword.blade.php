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
        .resetpassword-page{
            margin:0px auto !important;
            float: none;
        }
        .change-credentials-form {
            margin-top: 0px !important;
        }
        .lock-image{
            margin: 0px auto !important;
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
                       
                        <div class="col-md-6 resetpassword-page">
                            <div class="account-settigs-content">
                                <div class="setting_title text-center">
                                    <h1>Change your password</h1>
                                    <span>Change your account Login Credentials</span>
                                </div>
                                
                                <form class="account_form text-center" method="post" action="{{ route('reset.password') }}">
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

@endpush