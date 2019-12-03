<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
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
    <link href="{{asset('/')}}assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('/')}}assets/css/style.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tooltipster.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css')}}" >
   
    <link rel="stylesheet" href="{{ asset('assets/css/alertify/alertify.rtl.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/alertify/themes/default.css')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    
    <script type="text/javascript" src="http://www.hapity.com/assets/js/fb.js"></script>
    <script src="{{ asset('assets/js/jwplayer.js') }}"></script>
    <script type='text/javascript'>jwplayer.key='fyA++R3ayz2ubL4Ae9YeON9gCFRk3VUZo+tDubFgov8=';</script>    

    <style>
      @media (max-width: 767px) {
        .maintenanemode-image{
          width: 100%;
        }
      }
    </style>
    </head>
<body>
    <div id="app">
<!-- header starts from here -->
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
              <a class="navbar-brand" href="http://dev.api.hapity.local">
                <img src="http://dev.api.hapity.local/assets/images/home-new/logo.png" width="90px">
              </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="main-nav nav navbar-nav navbar-right nav-color">

      
      
    </ul>
  </div><!-- /.navbar-collapse -->          </div><!-- /.container-fluid -->
        </nav>

    </div>
  </div>
</header><!-- header -->      
<!-- header  ends here -->

        
            <div class="profile-page new_design">
    <div class="section-main">
      <div class="container"> 
          <p class="text-center">
              <img src="{{asset('assets/images/maintenance.png')}}" class="maintenanemode-image" />      
          </p>    
          
          <br>
          <h2 class="text-center">Maintenance Mode</h2>
      </div>
    </div>
  
  
  </div>
  <div class="clear"> <p><br><br></p></div>
          

<!-- footer starts from here -->
    <footer class="footer-wrapper">
    <div class="container">
      
    </div>
    
    <div class="container">
      <div class="row">
        <div class="col-md-5">
          <div class="copyrights-content">
            <p>&copy;2019 All Rights Reserved. </p>
          </div>
        </div>
      </div>
    </div>
  </footer><!-- footer ends here -->

    </div>

    
    <script src="{{ asset('assets/js/alertify.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery-ias.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/functions.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.tooltipster.js')}}"></script>

    <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
    
    <script src="{{ asset('assets/js/cropbox.js')}}"></script>
    <script src="https://js.pusher.com/2.2/pusher.min.js"></script>
    <script src="{{ asset('assets/js/jquery.loader.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/app.js')}}"></script>

    </body>
</html>
