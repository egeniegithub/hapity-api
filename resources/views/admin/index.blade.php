@extends('admin.master-layout')
@push('admin-css')

@endpush
@section('content')
    <!--Right Content Area start-->
    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="section-heading">
                        <p> Dashboard</p>
                    </div>
                </div>

                <!--Reported Broadcost Section start-->
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-info-wrapper">
                        <div class="info-header">REPORTED BROADCASTS</div>
                        <div class="info-body">
                            <div class="info-icon"><img src="{{asset('/assets/admin')}}/images/cam.png"/></div>
                            <div class="info-icon-text">
                                
                                {{ !empty($data[0]['reported_broadcast_count']) ? $data[0]['reported_broadcast_count'] : 0 }}
                            </div>
                        </div>
                        <div class="info-footer">
                            <a href="{{route('admin.reportedBroadcasts')}}">View more</a>
                        </div>
                    </div>
                </div>
                <!--Reported Broadcost Section start-->

                <!--Reported User Section start-->
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-info-wrapper">
                        <div class="info-header">REPORTED USERS</div>
                        <div class="info-body">
                            <div class="info-icon"><img src="{{asset('/assets/admin')}}/images/reported-user-lg-icon.png"/></div>
                            <div class="info-icon-text"> {{ !empty($data[0]['reported_user_count']) ? $data[0]['reported_user_count'] : 0 }}</div>
                        </div>
                        <div class="info-footer">
                            <a href="{{route('admin.reportedUsers') }}">View more</a>
                        </div>
                    </div>
                </div>
                <!--Reported User Section End-->

                <!--Live Broadcost Section start-->

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="dashboard-info-wrapper">
                        <div class="info-header">LIVE BROADCASTS</div>
                        <div class="info-body">
                            <div class="info-icon"><img src="{{asset('/assets/admin')}}/images/live-bc-lg-icon.png"/></div>
                            <div class="info-icon-text"> {{ !empty($data[0]['live_broadcast_count']) ? $data[0]['live_broadcast_count'] : 0 }}</div>
                        </div>
                        <div class="info-footer">
                            <a href="{{route('admin.broadcast')}}">View more</a>
                        </div>
                    </div>
                </div>
                <!--Live Broadcost Section End-->

                <!--Worldwide Section Start-->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="section-heading">
                        <p class="world-wide-text"> Live Broadcasts World Wide</p>
                    </div>
                    <div class="map-section">
                        <img src="{{asset('/assets/admin')}}/images/map.png" class="img-responsive" />
                    </div>
                </div>
                <!--Worldwide Section End-->

                
            </div>
        </div>
        <!--Right Content Area End-->
@endsection

@push('admin-script')

@endpush