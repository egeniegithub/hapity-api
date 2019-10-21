@extends('layouts.app')

@push('css')
    <style type="text/css">
        #app{
            background: #333;
        }
        #reset-submit{
            width: 362px !important;
            margin: 0px auto;
            margin-left: -14px !important;
        }

    </style>
@endpush

@section('content')
<div class="wrapper-outer">
    <div class="reset_password">
        {{-- <div class="logo"><a href="{{route('home')}}"><img src="{{ asset('assets/')}}/images/logo.png"></a></div> --}}
        <div class="form-area">
            <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                <h4>Enter your new password</h4>
                <input type="hidden" id="reset-email" value="<?php echo $email; ?>">

                <div class="form-group row">
                        <input id="email" type="email" class="form-fields @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="EMAIL">
                        @error('email')
                            <span class="invalid-feedback error-test-color" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                </div>
                <div class="form-group row">
                                <input id="password" type="password" class="form-fields @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="PASSWORD">

                                @error('password')
                                    <span class="invalid-feedback error-test-color" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group row">
                            
                                <input id="password-confirm" type="password" class="form-fields" name="password_confirmation" required autocomplete="new-password" placeholder="CONFIRM PASSWORD">
                            </div>
                        </div>

                <input class="form-fields btn-field " type="submit" value="RESET PASSWORD" id="reset-submit">
            </form>
        </div>
        <div class="clear"></div>
    </div>
    <br><br><br>

@endsection
