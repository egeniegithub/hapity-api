@extends('layouts.app')

@section('content')
<div class="login">
<div class="wrapper-outer">
<div class="wrapper login-new-wrapper"> 
  
  <div class="form-area">
    <form method="POST" action="{{ url('register') }}">
        @csrf
      <input id="username" type="text" placeholder="USERNAME" class="form-fields @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
        @error('username')
            <span class="invalid-feedback error-test-color" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>
      <input id="email" type="email" placeholder="EMAIL ADDRESS" class="form-fields @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
        @error('email')
            <span class="invalid-feedback error-test-color" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>
      <input id="password" type="password" placeholder="PASSWORD" class="form-fields @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
        @error('password')
            <span class="invalid-feedback error-test-color" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>
      <input id="password" type="password" placeholder="CONFIRM PASSWORD" class="form-fields @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">

        @error('password')
            <span class="invalid-feedback error-test-color" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
 
      <button type="submit" class="form-fields btn-field">
        {{ __('Register') }}
      </button>
       </form>
  </div>
  <div class="clear"></div>
  <div class="or-text-new-register">OR</div>
  <div class="social-media-login"> <a class="facebook fb-cursor" onClick="fb_login();">login with facebook</a> <a class="twitter" href="https://api.twitter.com/oauth/authenticate?oauth_token=HXTTgQAAAAAAhe0BAAABbYHsYKE">login with twitter</a> </div>
</div>


@endsection
