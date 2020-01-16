@extends('layouts.app')
@section('content')   

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
                        <a href="{{ route('broadcasts.create') }}" class="btn btn-block btn-hapity-dark btn-lg">
                            <i class="fa fa-camera"></i> Start Your Broadcast Here
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('broadcasts.upload') }}" class="btn btn-block btn-hapity-dark btn-lg">
                            <i class="fa fa-plus-square "></i> Create Content
                        </a>
                    </div>
                </div>

                <hr />
                @foreach($broadcasts as $broadcast_key => $broadcast)    
                   <a data-fancybox 
                        data-width="720" 
                        data-height="480"
                        href="javascript:void();" 
                        data-type="iframe" 
                        data-src="{{ '//stg-media.hapity.com:5443/WebRTCApp/play.html?name=' . pathinfo($broadcast->filename,  PATHINFO_FILENAME) }}"  href="javascript:void();">                
                        <div class="row">                  
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="panel panel-success panel-broadcast">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                <div class="thumbnail">
                                                    <img src="{{ asset('images/broadcasts/' . Auth::id() . '/' . $broadcast->broadcast_image) }}" alt="" />
                                                </div>
                                            </div>
                                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <h3 class="broadcast-title">{{ $broadcast->title }}</h3>
                                                <p class="short-desc">{{ $broadcast->description }}</p>
                                            </div>
                                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-right">
                                                <ul class="bordcast-edit-actions">
                                                    <li class="social-share-action">
                                                        <a href="#" data-toggle="modal" data-target="#share-modal">
                                                            <img src="http://dev.hapity.local/assets/images/share.png" alt="social Media" width="28">
                                                        </a>
                                                        <ul class="social-share-on" style="display: none;">
                                                            <li>
                                                                <a href="javascript:;" data-modal-id="embed-code-popup-{{ $broadcast->id }}" class="code-icon">
                                                                    <i class="fa fa-code"></i>
                                                                </a>
                                                            </li>                                                                                                                
                                                            <li>
                                                                <a href="https://twitter.com/intent/tweet?url={{ $broadcast->share_url }}" target="_blank" class="twitter">
                                                                    <i class="fa fa-twitter"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $broadcast->share_url }}" target="_blank">
                                                                    <i class="fa fa-facebook"></i>
                                                                </a>
                                                            </li>
                                                            
                                                        </ul>
                                                    </li>
                                                    <li class="social-share-action">
                                                        <a href="{{ route('broadcasts.edit', [$broadcast->id]) }}">
                                                            <img src="http://dev.hapity.local/assets/images/edit.png" alt="Edit" width="28">
                                                        </a>
                                                    </li>
                                                    <li class="social-share-action">
                                                        <a href="javascript:void();" data-broadcast_id="{{ $broadcast->id }}" class="delete-btn">
                                                            <img src="http://dev.hapity.local/assets/images/delete.png" alt="Delete" width="28">
                                                        </a>
                                                        <form id="form_delete_broadcast_{{ $broadcast->id }}" enctype="multipart/form-data" method="POST" action="{{ route('broadcasts.delete') }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_broadcast" />
                                                            <input type="hidden" id="broadcast_id" name="broadcast_id" value="{{ $broadcast->id }}" />                                                         
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                   </a>
                   
                   
                              
                    
                @endforeach                              
            </div>
        </div>
       
        <br />
        <br />
        <br />
    </div>
    
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <link href="https://vjs.zencdn.net/7.6.6/video-js.css" rel="stylesheet" />
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <script>
        $(document).ready(function(){
            $('body').on('click', '.delete-btn', function(){
                var broadcast_id = $(this).attr('data-broadcast_id');
                alertify.confirm(
                    'Are you sure?', 
                    'Are you sure you want to delete this broadcast?', 
                    function(){
                        $('#form_delete_broadcast_' + broadcast_id).submit();
                    }, 
                    function(){
                        alertify.error('Cancelled');
                    });
            });
        });
    </script>
@endpush





