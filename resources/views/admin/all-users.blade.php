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
                        <form action="{{route('admin.users')}}">
                            <input required name="search" type="text" placeholder="Search user..."/>
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <br>
                <div class="col-sm-12">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session('flash_message'))
                        <div class="alert alert-success">{{ session('flash_message') }}</div>
                    @endif
                </div>

            <!--Reported Broadcast listing start-->
            @foreach ($users as $user)
                @if(!in_array($user->id, $reported_user_ids) && !$user->hasRole(SUPER_ADMIN_ROLE_ID))
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="listing-reported-broadcost">
                            <div class="reporting-bc-image reported_user-image">
                                @if(!empty($user['profile']['profile_picture']))
                                <img src="{{ asset('images/profile_pictures'.'/'.$user['profile']['profile_picture'] )}}"/>
                                @else
                            <img src="{{asset('assets/images/null.png')}}" >
                                @endif
                            </div>

                        <div class="reported-bc-detail">
                            <p> <span class="title"><a href="{{url('admin/broadcast')}}?username={{ $user['username'] }}">{{ ucwords($user['username']) }}</a></span></p>
                            <p>  <span class="reportby">Broadcasts :</span> <span class="report-result-display"> {{ !empty($user['broadcasts']) ? count($user['broadcasts']) : 0 }}</span></p>
                            <p>  <span class="reportby">Email :</span> <a href="mailto:{{ $user['email'] }}" class="report-result-display"> {{ $user['email'] }}</a></p>
                            <p>  <span class="reportdate">Registered :</span> <span class="report-result-display"> {{ date("d M Y", strtotime($user['created_at'])) }}</span></p>
                        </div>

                        <div class="report-bc-action-div">
                            <form method="post" action="{{ route('admin.deleteuser') }}" id="form-{{ $user['id'] }}">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                                <input type="button" name="" class="delete-block-bc btn btn-danger del-all-bc-single" value="Delete" onclick="confirmDelete({{ $user['id'] }})">
                            </form>

                        
                        </div>
                    </div>
                </div>
                @endif
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
    
    <script type="text/javascript">

        function confirmDelete(user_id){
           
            alertify.confirm('Are you sure you want to delete? ',function(e){
            if(e) {
                $('#form-'+user_id).submit();
                return true;
            } else {
                return false;
            }
        }).setHeader('<em> Delete User</em> ').set('labels', {ok:'Yes', cancel:'Cancel'});
    }

    </script>
@endpush