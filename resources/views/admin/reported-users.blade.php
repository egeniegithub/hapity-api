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
    
                        <!--Reported Broadcast listing start-->
                        <?php foreach ($reported_users as $user) { ?>
                        @php @endphp
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="listing-reported-broadcost">
                                    <div class="reporting-bc-image reported_user-image">
                                        <img src="<?php echo $user['profile']['profile_picture']; ?>"/>
                                    </div>
                                <div class="reported-bc-detail">
                                    <p> <span class="title"><?php echo ucwords($user['username']) ;?></span></p>
                                    <p>  <span class="reportby">Reports :</span> <span class="report-result-display"> {{ !empty($user['reported_user']) ? count($user['reported_user']) : 0 }}</span></p>
                                    <p>  <span class="reportdate">Registered :</span> <span class="report-result-display"> <?php echo $user['join_date']; ?></span></p>
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
                                    {{ $reported_users->links() }}
                                </nav>
                            </div>
                        </div>
                        <!--Pagination End-->                        <!--Footer Section Start-->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <footer>
                                <div class="copyright-text">Copyright &copy; 20015-2016 Hapity. All rights reserved.</div>
                            </footer>
                        </div>
                        <!--Footer Section End-->
                    </div>
                </div>
                <!--Right Content Area End-->
            </div>
        </div>
    </div>
    <!--Main End-->
    </div>
    <!--Wrapper End-->
    </body>
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