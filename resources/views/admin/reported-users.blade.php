@extends('admin.master-layout')
@push('admin-css')
    <style type="text/css">
        .delete-button{
            margin-top: 4px !important;
        }
    </style>
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
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if (Session::has('flash_message'))
                                    
                                    <div class="alert alert-success">{{ Session::get('flash_message') }}</div>
                                @endif
                                @if(Session::has('flash_message_delete'))
                                    
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
                                    <form method="post" action="{{ route('admin.approveduser') }}" id="approved-form-{{ $user['id'] }}">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                                        <input type="button" name="" class="btn btn-primary approve-block-bc" value="Approve" onclick="confirmApproved({{ $user['id'] }})">
                                    </form>

                                <form method="post" action="{{ route('admin.deleteuser') }}" id="delete-form-{{ $user['id'] }}">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                                    <input type="button" name="" class="btn btn-danger delete-block-bc del-all-bc-single delete-button" value="Delete" onclick="confirmDelete({{ $user['id'] }})" id="">
                                </form>

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
    <script type="text/javascript">
        
        function confirmDelete(user_id){
            alertify.confirm('Are you sure you want to delete? ',function(e){
            if(e) {
                $('#delete-form-'+user_id).submit();
                return true;
            } else {
                return false;
            }
        }).setHeader('<em> Delete User</em> ').set('labels', {ok:'Yes', cancel:'Cancel'});;
    }
    
    function confirmApproved(user_id){
            alertify.confirm('Are you sure you want to approved? ',function(e){
            if(e) {
                $('#approved-form-'+user_id).submit();
                return true;
            } else {
                return false;
            }
        }).setHeader('<em> Approve User</em> ').set('labels', {ok:'Yes', cancel:'Cancel'});;
    }
    </script>
@endpush