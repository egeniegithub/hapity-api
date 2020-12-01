@extends('layouts.app')
@section('content')

    <script>
        $('body').on('click', '.delete-btn', function(){
            var broadcast_id = $(this).attr('data-broadcast_id');
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete this broadcast key?',
                function(){
                    $('#form_delete_broadcast_' + broadcast_id).submit();
                },
                function(){
                    alertify.error('Cancelled');
                });
        });
    </script>


    <div class="container">
        <br />

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
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('broadcasts.create_obs_key') }}" class="btn btn-block btn-hapity-dark btn-lg">
                            <i class="fa fa-plus-square "></i> Add new stream key for OBS
                        </a>
                    </div>
                </div>
                <h3>Settings to enter in OBS</h3>
                <strong>Service : Custom</strong><br>
                <strong>Server : rtmp://antmedia.hapity.com/WebRTCAppEE</strong><br>
                <strong>Stream Key : Choose from below stream keys</strong><br>

                <hr />
                    <table class="table table-hover">
                        <thead>
                            <tr>
                              <th>Broadcat Title</th>
                              <th>Stream Key</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                                @if(count($broadcasts) > 0)
                                    @foreach($broadcasts as $broadcast_key => $broadcast)
                                    <tr>
                                        <td>{{ $broadcast->title }}</td>
                                        <td>{{ str_replace(".mp4","",str_replace("_720p.mp4","",$broadcast->video_name)) }}</td>
                                        <td>
                                            <a href="javascript:void();" data-broadcast_id="{{ $broadcast->id }}" class="delete-btn">
                                                <img src="{{ asset('assets') }}/images/delete.png" alt="Delete" width="28">
                                            </a>
                                            <form id="form_delete_broadcast_{{ $broadcast->id }}" enctype="multipart/form-data" method="POST" action="{{ route('broadcasts.delete') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_broadcast" />
                                                <input type="hidden" id="broadcast_id" name="broadcast_id" value="{{ $broadcast->id }}" />
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">No stream key found</td>
                                    </tr>
                                @endif
                          </tbody>
                  </table>
                  {{ $broadcasts->links() }}
            </div>
        </div>

        <br />
        <br />
        <br />
    </div>

@endsection



