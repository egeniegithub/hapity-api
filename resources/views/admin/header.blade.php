<html lang="en">
<head>
    <title>Hapity</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/admin')}}/css/style.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/admin')}}/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/admin')}}/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/admin')}}/css/responsive.css">
    <link rel="stylesheet" href="{{asset('assets/admin')}}/css/alertify/themes/default.css">
    <link rel="stylesheet" href="{{asset('assets/admin')}}/css/alertify/alertify.min.css">
    

    <script src="{{asset('/assets/admin')}}/js/jquery.js"></script>
    <script src="{{asset('/assets/admin')}}/js/functions.js"></script>
    <script src="{{asset('/assets/admin')}}/js/bootstrap.min.js"></script>
    <script src="{{asset('/assets/admin')}}/js/app.js"></script>
    <script src="{{asset('assets/admin')}}/js/alertify.js"></script>

    <script src="{{asset('assets/admin')}}/js/jwplayer.js"></script>
    <script type='text/javascript'>
        jwplayer.key='fyA++R3ayz2ubL4Ae9YeON9gCFRk3VUZo+tDubFgov8=';
    </script>
    <style type="text/css">
        .alertify .ajs-dialog {
	border: none;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;
}

.alertify .ajs-header {
	background: #67a04f !important;
	color: #fff !important;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;
}

.alertify .ajs-commands button.ajs-close {
	background-color: #fff !important;
	border-radius: 20px;
	color: #fff !important;
}

.alertify .ajs-footer {
	padding: 5px;
}

.alertify .ajs-footer .ajs-buttons .ajs-button {
	border: 1px solid #67a04f !important;
	font-weight: normal !important;
	border-radius: 4px !important;
	background-color: #67a04f ;
	color: #fff !important;
	cursor: pointer;
}

.alertify .ajs-footer .ajs-buttons .ajs-button.ajs-cancel {
	background-color: #55acee;
	border-color: #55acee !important;
}
    </style>

    @stack('admin-css')
</head>
<body id="close-modal">
        <!--Wrapper Start-->
        <div class="wrapper">
            <!--Header start-->
            <header>
                <div class="header-section">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="heade-logo">
                                    <img src="{{asset('/assets/admin')}}/images/dashboard-logo.png"/>
                                </div>
                                <div class="logout-button">
                                    <a href="{{route('logout')}}" class="logout">Log out</a>
                                </div>
    
                            </div>
                        </div>
    
                    </div>
                </div>
            </header>
            <!--Header End-->
            <div class="main">
                    <div class="container-fluid">
                        <div class="row">
                            <!--Left Sidebar start-->
                            <div id="left_area" class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                @include('admin.sidebar')
                            </div>
                            @yield('content')