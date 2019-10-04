@extends('admin.master-layout')
@push('admin-css')

@endpush
@section('content')

                <!--Right Content Area start-->
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12" id="height-section">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="section-heading">
                                <p> Reported Users</p>
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
    
                        <!--Reported Broadcast listing start-->
                        <?php foreach ($reported_users as $key => $user) { if($user->hasRole(SUPER_ADMIN_ROLE_ID)) continue;  ?>
                        @php @endphp
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="listing-reported-broadcost">
                                    <div class="reporting-bc-image reported_user-image">
                                        @if(!empty($user['profile']['profile_picture']))
                                        <img src="<?php echo $user['profile']['profile_picture']; ?>"/>
                                        @else
                                    <img src="{{asset('assets/images/null.png')}}" >
                                        @endif
                                    </div>
                                <div class="reported-bc-detail">
                                    <p> <span class="title"><?php echo ucwords($user['username']) ;?></span></p>
                                    <p>  <span class="reportby">Reports :</span> <span class="report-result-display"> {{ !empty($user['reportedUser']) ? count($user['reportedUser']) : 0 }}</span></p>
                                    <p>  <span class="reportdate">Registered :</span> <span class="report-result-display"> <?php echo $user['created_at']; ?></span></p>
                                </div>
    
                                <div class="report-bc-action-div">
                                    <a href="{{ url('admin/approveduser/'.$user['id']) }}" class="approve-block-bc">Approve</a>
                                    <a href="{{url('admin/deleteuser'.'/'.$user['id'])}}"  class="delete-block-bc">Delete</a>
                                </div>
                            </div>
                        </div>
                        <?php
                              }
                        ?>
                        <!--Reported Broadcast listing End-->
    
                        <!--Pagination start-->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="report-bc-pagination">
                                <nav>
                                    {{ !empty($reported_users) ? $reported_users->links() : '' }}
                                </nav>
                            </div>
                        </div>
                      
                    </div>
                </div>
                <!--Right Content Area End-->
       
    <?php
    if(isset($_GET['approved'])) {?>
        <script>
            alert('User is approved successfully.')
        </script>
    <?php } else if(isset($_GET['delete'])) {?>
        <script>
            alert('User is deleted successfully.')
        </script>
    <?php }?>
    </html>
@endsection

@push('admin-script')

@endpush