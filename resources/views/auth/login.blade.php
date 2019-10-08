@extends('layouts.app')

@section('content')

<div class="login">
<div class="wrapper login-new-wrapper"> 
  <div class="form-area">
    <form method="POST" action="{{ route('login') }}">
        @csrf
      <input id="login" type="text" class="form-fields @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus placeholder="EMAIL ADDRESS">
      @error('login')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      <input id="password" type="password" placeholder="PASSWORD" class="form-fields @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      <input class="form-fields btn-field" type="submit" value="LOGIN" id="login-submit">
    </form>
  </div>
  <div class="forgot-password-area">
    <ul>
      <li><a href="#">
        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
               Keep me logged in</a></li>
        <li>
            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgotten your password?') }}
                </a>
            @endif
        </li>
    </ul>
  </div>
  <div class="signup-area">
    <p>Don’t have an account yet?</p>
    <a href="{{route('register')}}">Register yourself now</a>
    </div>
    <div class="social-media-login"> 
      <a class="facebook fb-cursor" href="{{ route('auth.social', 'facebook') }}">login with facebook</a> 
      <a class="twitter" href="https://api.twitter.com/oauth/authenticate?oauth_token=HXTTgQAAAAAAhe0BAAABbYHsYKE">login with twitter</a> 
    </div>
  <div class="clear"></div>
</div>


@endsection
