@extends('layouts.app')
@section('content')

    <div class="container">
        <br />
        <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                        <h1 class="broadcast-heading">Dashboard<br /><small>Manage Your Broadcasts</small></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="profile-section-disable">
                    <div class="profile-picture">
                        <figure>
                          @if(isset(Auth::user()->profile->profile_picture) && !empty(Auth::user()->profile->profile_picture))
                            <img src="{{asset('images/profile_pictures/'.Auth::user()->profile->profile_picture)}}">
                          @else
                             <img src="{{ asset('assets/images/null.png') }}">
                          @endif
                        </figure>
                        <div class="text">
                            <h2>
                                <a href="{{route('settings')}}">
                                  @if(Auth::user())
                                    {{ Auth::user()->username }}
                                  @endif
                                </a>
                            </h2>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">

                @if(Session::has('message_success'))
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Success!</strong> {{ Session::get('message_success')}}
                            </div>
                        </div>
                    </div>
                @endif
                @if(Session::has('message_error'))
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Error!</strong> {{ Session::get('message_error')}}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well well-sm" >
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <a href="{{ route('broadcasts.index') }}" class="btn btn-block btn-lg">
                                        <div class="panel panel-success">
                                            <div class="panel-body text-center" style="padding: 60px; color: #97be0d">
                                                <i class="fa fa-fw fa-5x fa-camera"></i>
                                                <h3>Your Broadcasts<br /><small>Find All your Broadcasts here</small></h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <a href="{{ route('broadcasts.create') }}" class="btn btn-block btn-lg">
                                        <div class="panel panel-success">
                                            <div class="panel-body text-center" style="padding: 60px; color: #97be0d">
                                                <i class="fa fa-fw fa-5x fa-camera"></i>
                                                <h3>Create Broadcast<br /><small>Create Something Awesome</small></h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <a href="{{ route('broadcasts.upload') }}" class="btn btn-block btn-lg">
                                        <div class="panel panel-success">
                                            <div class="panel-body text-center" style="padding: 60px; color: #97be0d">
                                                <i class="fa fa-fw fa-5x fa-camera"></i>
                                                <h3>Upload Broadcast<br /><small>Upload a Recorded Video</small></h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <a href="{{ route('settings') }}" class="btn btn-block btn-lg">
                                        <div class="panel panel-success">
                                            <div class="panel-body text-center" style="padding: 60px; color: #97be0d">
                                                <i class="fa fa-fw fa-5x fa-cogs"></i>
                                                <h3>Account Settings<br /><small>Manage Your Account</small></h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                {{--<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <a href="{{ route('broadcasts.list_obs_keys') }}" class="btn btn-block btn-lg">
                                        <div class="panel panel-success">
                                            <div class="panel-body text-center" style="padding: 60px; color: #97be0d">
                                                <i class="fa fa-fw fa-5x fa-key"></i>
                                                <h3>OBS Stream Keys<br><small>Create OBS Broadcast Keys</small></h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>--}}
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <a href="{{ route('resetpassword') }}" class="btn btn-block btn-lg">
                                        <div class="panel panel-success">
                                            <div class="panel-body text-center" style="padding: 60px; color: #97be0d">
                                                <i class="fa fa-fw fa-5x fa-lock"></i>
                                                <h3>Security<br /><small>Reset Your Password</small></h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <br />
        <br />
        <br />
    </div>

@endsection

@push('css')

@endpush

@push('script')

@endpush





