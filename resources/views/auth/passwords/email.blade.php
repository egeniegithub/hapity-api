@extends('layouts.app')

@section('content')


<div class="login forget_pass_page">
    
    
    <div class="wrapper-outer">

    <div class="forget_password login-new-wrapper">
        
        <!--<div class="logo"><a href="#"><img src="<?php //echo base_url('assets/');?>/images/logo.png"></a></div>-->
        <div class="form-area">
            @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                            <h4>Forgot Your Password?</h4>
                            <div class="form_field">
                            <label>Enter your email</label>
                {{-- <input class="form-fields" type="email" name="email" id="email-forget" required> --}}
                <input id="email" type="email" class="form-fields @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email address">

                @error('email')
                    <span class="invalid-feedback error-test-color" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                            </div>
                <input class="form-fields btn-field submit_btn" type="submit" value="Reset password" id="forget-submit">
                {{-- <button type="submit" class="btn btn-primary">
                    {{ __('Send Password Reset Link') }}
                </button> --}}
            </form>
        </div>
        <div class="clear"></div>
                <div class="or-text-new-register">OR</div>
                <div class="social-media-login">
                    <a class="facebook fb-cursor" onClick="fb_login();">login with facebook</a>
                    <a class="twitter" href="<?php //echo $twLoginUrl; ?>">login with twitter</a>
                </div>
    </div>
    <div class="clearfix"></div>


{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
