@extends('admin.master-layout')
@push('admin-css')

@endpush
@section('content')

                <!--Right Content Area start-->
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12" id="height-section">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="section-heading">
                                <p>Settings</p>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-sm-12">
                                    @if (Session::has('flash_message'))
                                        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                                        <div class="alert alert-success">{{ Session::get('flash_message') }}</div>
                                    @endif
                                    @if(Session::has('flash_message_delete'))
                                        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                                        <div class="alert alert-danger">{{ Session::get('flash_message_delete') }}</div>
                                    @endif
                                    
                                </div>
                            </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    
                        <form role="form" method="post" action="{{url('admin/changepassword')}}">
                            @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">User Name</label>
                                <input type="text" class="form-control" id="username" value="{{ Auth::user()->username }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Old Password</label>
                                    <input required type="password" name="oldpass" class="form-control old-pass" id="oldpass" placeholder="Old Password">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">New Password</label>
                                    <input required type="password" class="form-control new-pass" name="newpass" id="newpass" placeholder="New Password">
                                </div>
                                <button type="submit" class="btn btn-default" id="reset-passwords">Submit</button>
                            </form>
    
                        </div>
                     
                    </div>
                </div>
    
            </div>
        </div>
    </div>
    
    
    </div>
    <!--Wrapper End-->
    </body>
    </html>
@endsection

@push('admin-script')

@endpush