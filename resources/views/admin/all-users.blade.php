@extends('admin.master-layout')
@push('admin-css')

@endpush
@section('content')
<div class="col-lg-10 col-md-10 col-sm-8 col-xs-12" id="height-section">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="section-heading">
                    <p> All Users</p>
                    <div class="all-bc-search">
                        <form action="{{url('admin/users')}}">
                            <input required name="search" type="text" placeholder="Search user..."/>
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <!--Reported Broadcast listing start-->
            @foreach ($users as $user)

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="listing-reported-broadcost">
                            <div class="reporting-bc-image reported_user-image">
                                <img src="<?php echo $user['profile_picture']; ?>"/>
                            </div>

                        <div class="reported-bc-detail">
                            <p> <span class="title">{{ ucwords($user['username']) }}</span></p>
                            <p>  <span class="reportby">Broadcasts :</span> <span class="report-result-display"> {{ $user['user_broadcast_count'] }}</span></p>
                            <p>  <span class="reportby">Email :</span> <a href="mailto:{{ $user['email'] }}" class="report-result-display"> {{ $user['email'] }}</a></p>
                            <p>  <span class="reportdate">Registered :</span> <span class="report-result-display"> {{ date("d M Y", strtotime($user['join_date'])) }}</span></p>
                        </div>

                        <div class="report-bc-action-div">
                            <a href="javascript:" onclick="del_a_user({{ $user['id'] }});" class="delete-block-bc del-all-bc-single">Delete</a>
                        </div>
                    </div>
                </div>
                
            @endforeach
            <!--Reported Broadcast listing End-->

            <!--Pagination start-->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="report-bc-pagination">
                    <nav>
                        {{$users->links()}}
                    </nav>
                </div>
            </div>
           
        </div>
    </div>
@endsection

@push('admin-script')

@endpush