@extends('layouts.app')
@push('css')
  <style type="text/css">
    .login-error{
      color: red;
    }
    .wrapper{
    	background-color: #383838;
    }
  </style>
@endpush
@section('content')
<div class="login">
	<div class="wrapper login-new-wrapper">
		<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
				<div class="form-area">
					@if($errors->any())
						<h4 class="login-error">{{$errors->first()}}</h4>
					@endif

					<form method="POST" action="{{ route('login') }}">
						@csrf
						<input id="login" type="text" class="form-fields @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus placeholder="EMAIL ADDRESS">
						@error('login')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span> <br>
						@enderror
						<input id="password" type="password" placeholder="PASSWORD" class="form-fields @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
						@error('password')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>  <br>
						@enderror
						<input class="form-fields btn-field" type="submit" value="LOGIN" id="login-submit">
					</form>
				</div>
				<div class="forgot-password-area">
					<ul>
						<li><a href="javascript::void()">
						<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
								<label for="remember">Keep me logged in</label></a></li>
						<li>
							@if (Route::has('password.request'))
								<a class="btn btn-link" href="{{ route('password.request') }}">
									{{ __('Forgotten your password?') }}
								</a>
							@endif
						</li>
					</ul>
				</div>
				{{--<div class="signup-area">
					<p>Don’t have an account yet?</p>
					<a href="{{route('register')}}">Register yourself now</a>
				</div>
				<div class="social-media-login">
					<a class="facebook fb-cursor" href="{{ route('auth.social', 'facebook') }}">login with facebook</a>
					<a class="twitter" href="{{ route('auth.social','twitter') }}">login with twitter</a>
				</div>
				<div class="clear"></div>--}}
			</div>
		</div>
	</div>
</div>


@endsection
