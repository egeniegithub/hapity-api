@extends('layouts.app')
  @push('css')
    <style type="text/css">
      .term-condition{
        color: #fff;
        font-family: sans-serif;
      }
      .forgot-password-area ul li {
          float: left;
          width: 100%;
          list-style: none;
      }
      .error-test-color{
        color: red !important;
      }
      .wrapper{
        background-color: #383838;
      }
    </style>
  @endpush
@section('content')
<div class="login">
<div class="wrapper-outer">
<div class="wrapper login-new-wrapper"> 
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
    <div class="form-area">
      <form method="POST" action="{{ route('register') }}">
          @csrf
        <input id="username" type="text" placeholder="USERNAME" class="form-fields @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
          @error('username')
              <span class="invalid-feedback error-test-color text-danger" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
          <br>
        <input id="email" type="email" placeholder="EMAIL ADDRESS" class="form-fields @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
          @error('email')
              <span class="invalid-feedback error-test-color text-danger" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
          <br>
        <input id="password" type="password" placeholder="PASSWORD" class="form-fields @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
          @error('password')
              <span class="invalid-feedback error-test-color text-danger" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
          <br>
        <input id="password" type="password" placeholder="CONFIRM PASSWORD" class="form-fields @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">

          @error('password')
              <span class="invalid-feedback error-test-color text-danger" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        <div class="forgot-password-area">
          <ul>
            <li>
              <span>
                <input class="form-check-input" type="checkbox" name="agree_terms_and_conditions" id="agree_terms_and_conditions" {{ old('agree_terms_and_conditions') ? 'checked="checked"' : '' }} required="" value="1">
              </span> 
              <span class="term-condition"><label for="agree_terms_and_conditions"> I agree to the Hapity</label> <a href="{{ route('privacy-policy') }}" target="_blank">Terms and Conditions </a> </span>
              <br>
              @error('agree_terms_and_conditions')
                <span class="invalid-feedback error-test-color text-danger" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <br>
              </li>
              <li>
                  @if (Route::has('password.request'))
                      
                  @endif
              </li>
          </ul>
        </div>
        <button type="submit" class="form-fields btn-field">
          {{ __('Register') }}
        </button>
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


@endsection

@push('script')

@endpush
