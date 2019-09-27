<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <?php
//    print_r($broadcast);
    //if(isset($broadcast)) {
    ?>
        <meta property="og:title" content="<?php //echo $broadcast['title']?>"/>
        <meta property="og:type" content="website" />
        <meta property="og:image" content="<?php// echo $broadcast['broadcast_image']?>" />
        <meta property="og:url" content="<?php // echo base_url().'/main/view_broadcast/'.$broadcast['id'];?>" />
        <meta property="og:description" content="<?php// echo $broadcast['title']?>" />
        <meta property="twitter:creator" content="gohapity" />
        <meta property="twitter:site" content="gohapity" />
        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:description" content="<?php// echo $broadcast['title']?>" />
        <meta property="twitter:title" content="<?php //echo $broadcast['title']?>" />
        <meta property="twitter:image:src" content="<?php //echo $broadcast['broadcast_image']?>" />
    <?php //}?>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/tooltipster.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css')}}" >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('js/css/alertify/alertify.rtl.css')}}">
    <link rel="stylesheet" href="{{ asset('css/alertify/themes/default.css')}}">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <script src="{{ asset('js/alertify.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-ias.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/functions.js')}}"></script>
    <script src="{{ asset('js/jquery.tooltipster.js')}}"></script>
</head>
<body>
    <div id="app">
<!-- header starts from here -->
<header class="header-wrapper">
    <div class="container">
      <div class="header-nav">
 <?php // if($this->session->userdata('user_id')==''){ ?>
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
              <a class="navbar-brand" href="">
                <img src="{{ url('assets/images/home-new/logo.png')}}" width="90px">
              </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right nav-color">

                <li><a href="<?php // echo base_url('home'); ?>">Home</a></li>
                <li><a href="<?php // echo base_url('home/login'); ?>">Login</a></li>
                <li><a href="<?php // echo base_url('register'); ?>">Register</a></li>
                <!-- <li><a href="<?php // echo base_url('about'); ?>">Who we are</a></li> -->
                <li><a href="http://blog.hapity.com/">Blog</a></li>
                <li><a href="<?php // echo base_url('help'); ?>">Help</a></li>
                <li><a href="<?php // echo base_url('about'); ?>#ContactFormSubmit">Contact</a></li>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
<?php // } else { ?>

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
              <a class="navbar-brand" href="<?php // echo base_url('home'); ?>">
                <img src="{{ url('assets/images/home-new/logo.png')}}" width="90px">
              </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right nav-color">
                <li><a href="<?php // echo base_url('/main/'); ?>" class="sign_in">Home</a></li>
                <li><a href="<?php // echo base_url('main/settings'); ?>" class="sign_in">settings</a></li>
                <li><a href="http://blog.hapity.com/">Blog</a></li>
                <li><a href="<?php // echo base_url('help');?>" class="sign_in">Help</a></li>
                <li><a href="<?php // echo base_url('about'); ?>#ContactFormSubmit">Contact</a></li>
                <li><a href="<?php // echo base_url('home/log_out'); ?>" class="sign_in">Logout</a></li>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>

<?php //} ?>
    </div>
  </div>
</header><!-- header -->      
<!-- header  ends here -->

        <main class="py-4">
            @yield('content')
        </main>

<!-- footer starts from here -->
    <footer class="footer-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-md-5">
          <div class="footer-content-widget">
            <p><a href="#."><img src="{{ url('assets/images/home-new/logo.png')}}" alt="logo" width="80px"></a></p>
            <p>Create your free livestream with Hapity: straight from<br>
             your mobile or computer to your website,and social media.<br>
             Creating livestreams has never been so easy.</p>
            <p style="margin-top: 20px;">
              <a href="https://itunes.apple.com/mt/app/hapity/id1068976447?mt=8" target="_blank"><img src="{{ url('assets/images/home-new/app-store.png')}}"></a>
              <a href="https://play.google.com/store/apps/developer?id=hapity.com" target="_blank"><img src="{{ url('assets/images/home-new/gplay-btn.png')}}"></a>
            </p>
          </div>
        </div>
        <div class="col-md-7">
          <div class="row">
            <div class="col-md-4">
              <div class="main-column-4-styling">
                <h5 class="text-uppercase">Quick links</h5>
                <ul>
                  <?php // if($this->session->userdata('user_id')==''){ ?>

                  <li><a href="<?php // echo base_url('home'); ?>">Home</a></li>
                  <li><a href="<?php // echo base_url('home/login'); ?>">Login</a></li>
                  <li><a href="<?php // echo base_url('register'); ?>">Register</a></li>
                  <!-- <li><a href="<?php // echo base_url('about'); ?>">Who we are</a></li> -->
                  <li><a href="http://blog.hapity.com/">Blog</a></li>
                  <li><a href="<?php // echo base_url('help'); ?>">Help</a></li>
                  <li><a href="<?php // echo base_url('about'); ?>#ContactFormSubmit">Contact</a></li>
            <?php // } else { ?>
                  <li><a href="<?php // echo base_url('/main/'); ?>" class="sign_in">Home</a></li>
                  <li><a href="<?php // echo base_url('main/settings'); ?>" class="sign_in">settings</a></li>
                  <li><a href="http://blog.hapity.com/">Blog</a></li>
                  <li><a href="<?php // echo base_url('help');?>" class="sign_in">Help</a></li>
                  <li><a href="<?php // echo base_url('about'); ?>#ContactFormSubmit" class="sign_in">Contact</a></li>
                  <li><a href="<?php // echo base_url('home/log_out'); ?>" class="sign_in">Logout</a></li>
            <?php // }?>
                </ul>
              </div>
            </div>
            <div class="col-md-4">
              <div class="main-column-4-styling">
                <h5 class="text-uppercase">links</h5>
                <ul>
                  <!-- <li><a href="">Terms & Conditions</a></li> -->
                  <li><a href="<?php // echo base_url('privacy-policy'); ?>">Privacy Policy</a></li>
                  <li><a href="<?php // echo base_url('help'); ?>">How it Works</a></li>
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
            <p>Hapity® is a registered trademark. <a href="<?php // echo base_url('privacy-policy'); ?>">Privacy and Terms</a><br> &copy;2019 All Rights Reserved. </p>
          </div>
        </div>
      </div>
    </div>
  </footer><!-- footer ends here -->

    </div>
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <!-- <script type="text/javascript" src="{{ url('assets/js/jquery-1.11.1.min.js')}}"></script> -->
    <script src="{{ url('assets/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ url('assets/js/jquery.main.js')}}"></script>
    <script src="{{ url('assets/js/cropbox.js')}}"></script>
    <script src="//js.pusher.com/2.2/pusher.min.js"></script>
    <script src="{{ url('assets/js/jquery.loader.js')}}"></script>
    <script type="text/javascript" src="{{ url('assets/js/app.js')}}"></script>
</body>
</html>
