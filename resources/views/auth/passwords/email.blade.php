@extends('layouts.app')
@push('css')
    <style type="text/css">
        .error-test-color{
            color: red !important;
          }
          .wrapper{
            background-color: #383838;
        }
    </style>
@endpush
@section('content')


<div class="login forget_pass_page">
    
    
    <div class="wrapper-outer">

    <div class="forget_password login-new-wrapper">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">   
                {{-- <div class="logo"><a href="#"><img src="{{ asset('assets/') }}/images/logo.png"></a></div> --}}
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
                        <input id="email" type="email" class="form-fields @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email address">

                        @error('email')
                            <span class="invalid-feedback error-test-color" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                                    </div>
                        <input class="form-fields btn-field submit_btn" type="submit" value="Reset password" id="forget-submit">
                      
                    </form>
                </div>
                <div class="clear"></div>
                <div class="or-text-new-register">OR</div>
                <div class="social-media-login">
                    <a class="facebook fb-cursor" href="{{ route('auth.social', 'facebook') }}">login with facebook</a>
                    <a class="twitter" href="{{ route('auth.social','twitter') }}">login with twitter</a>
                </div>
            </div>
        </div>    
    </div>
    <div class="clearfix"></div>

@endsection
