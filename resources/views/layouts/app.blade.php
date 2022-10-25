<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('sharing_post_card')

    @if(isset($broadcast) && !empty($broadcast))

        {{-- <meta property="og:title" content="{{ $broadcast[0]['title'] }}"/> --}}
        <meta property="og:type" content="website" />
        {{-- <meta property="og:image" content="{{ $broadcast[0]['broadcast_image'] }}" /> --}}
        <meta property="og:url" content="<?php // echo base_url().'/main/view_broadcast/'.$broadcast['id'];?>" />
        {{-- <meta property="og:description" content="{{ $broadcast[0]['title'] }}" /> --}}
        <meta property="twitter:creator" content="gohapity" />
        <meta property="twitter:site" content="gohapity" />
        <meta property="twitter:card" content="summary_large_image" />
        {{-- <meta property="twitter:description" content="{{ $broadcast[0]['title'] }}" /> --}}
        {{-- <meta property="twitter:title" content="{{ $broadcast[0]['title'] }}" /> --}}
        {{-- <meta property="twitter:image:src" content="{{ $broadcast[0]['broadcast_image'] }}" /> --}}
    @endif

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tooltipster.css')}}" >

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css')}}?v=2" >
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css')}}" >

    <link rel="stylesheet" href="{{ asset('assets/css/alertify/alertify.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/alertify/themes/default.min.css')}}">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    @stack('css')

	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

    <script type="text/javascript" src="{{asset('assets/js/fb.js')}}"></script>

</head>
<body>
    <div id="app" class="wrapper">
		<header class="header-wrapper">
			<div class="container">
				<div class="header-nav">
					<nav class="navbar navbar-default bg-color">
						<div class="container-fluid">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="{{route('home')}}">
									<img src="{{ asset('assets/images/home-new/logo.png')}}" width="90px">
								</a>
							</div>

							@include('layouts.top-menubar')
						</div>
					</nav>
				</div>
			</div>
		</header>
        <div style="background-color: #ff9800; text-align: center">
            <p>
                <h3>Notice!</h3>
                <b>We will be shutting down hapity.com on 30th of November 2022. Please download all of your videos by going into your
                dashboard and clicking on the download button. We will not be liable for any data loss.</b>
            </p>
        </div>

		<main class="main-content">
			@yield('content')
		</main>

		<footer class="footer-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-5">
						<div class="footer-content-widget">
							<p><a href="{{route('home')}}"><img src="{{ asset('assets/images/home-new/logo.png')}}" alt="logo" width="80px"></a></p>
							<p>Create your free livestream with Hapity: straight from<br>your mobile or computer to your website,and social media.<br>Creating livestreams has never been so easy.</p>
							<p style="margin-top: 20px;">
								<a href="https://itunes.apple.com/mt/app/hapity/id1068976447?mt=8" target="_blank"><img src="{{ asset('assets/images/home-new/app-store.png')}}"></a>
								<a href="https://play.google.com/store/apps/developer?id=hapity.com" target="_blank"><img src="{{ asset('assets/images/home-new/gplay-btn.png')}}"></a>
							</p>
						</div>
					</div>
					<div class="col-md-7">
						<div class="row">
							<div class="col-md-4">
								<div class="main-column-4-styling">
									<h5 class="text-uppercase">Quick links</h5>
									@include('layouts.footer-menubar')
								</div>
							</div>
							<div class="col-md-4">
								<div class="main-column-4-styling">
									<h5 class="text-uppercase">links</h5>
									<ul>
										<li><a href="{{route('privacy-policy')}}">Privacy Policy</a></li>
										<li><a href="{{route('help')}}">How it Works</a></li>
									</ul>
								</div>
							</div>
							<div class="col-md-4">
								<div class="main-column-4-styling">
									<h5 class="text-uppercase">More links</h5>
									<ul>
										<li><a href="https://twitter.com/gohapity" target="_blank">Twitter</a></li>
										<li><a href="https://www.facebook.com/GoHapity/" target="_blank">Facebook</a></li>
										<li><a href="https://wordpress.org/plugins/wp-hapity/" target="_blank">Plugins for Wordpress</a></li>
										<li><a href="https://www.drupal.org/project/hapity" target="_blank">Plugins for Drupal</a></li>
										<li><a href="https://extensions.joomla.org/extensions/extension/communication/video-conference/hapity/" target="_blank">Plugins for Joomla</a></li>
                                    </ul>
                                    <ul id="primary-footer-social" data-selector-name="socialmedia">
                                        <li>
                                            <a target="_blank" class="btn-facebook" href="https://www.facebook.com/GoHapity/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a target="_blank" class="btn-twitter" href="https://twitter.com/gohapity"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                        </li>
                                    </ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-5">
						<div class="copyrights-content">
							<p>Hapity<sup>©</sup> is a trademark. <a href="{{route('privacy-policy')}}">Privacy and Terms</a><br> &copy;{{ date('Y')}} All Rights Reserved. </p>
						</div>
					</div>
				</div>
			</div>
		</footer>
    </div>



    <script src="{{ asset('assets/js/alertify.js')}}"></script>
    <script src="{{ asset('assets/js/functions.js')}}"></script>

    <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>

    <script src="{{ asset('assets/js/cropbox.js')}}"></script>
    <script src="{{ asset('assets/js/alertify.min.js')}}"></script>
	<script src="{{ asset('assets/js/jquery.loader.js')}}"></script>
	<script src="{{ asset('assets/jquery-filepond-1.0.0/filepond.jquery.js')}}"></script>


    <script src="{{ asset('assets/js/app.js')}}"></script>

    @stack('script')
</body>
</html>
