@extends('layouts.app')

@section('content')
<section class="banner-section">
    <div class="header-content">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="creating-livestream text-center">
              <h1>Creating <strong>free livestreams</strong> has never<br> been so easy.</h1>
            </div>
          </div>
        </div>
        <div class="video-section">
          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
              <div class="hapity-video">
                <div class="embed-responsive embed-responsive-16by9">
                  <video controls="" poster="{{ url('assets/images/home-new/hapity.png')}}" src="https://www.hapity.com/assets/videos/Hapity-Final-MP4.mp4" width="200" height="140"></video>
                </div>
              </div>
              <div class="home-transcript">
                <p><a href="<?php //echo base_url('assets/');?>/docs/Hapity Homepage Video Script.pdf" target="_blank">Transcript</a></p>
              </div>
            </div>
            <div class="col-md-3"></div>
          </div> <!-- row -->
        </div><!-- video section -->
      </div>
    </div>
  </section><!-- banner-section -->
  <section class="main-content">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="your-content">
            <h2>Your Content. Your Rules.</h2>
            <p>Rapidly publish free livestreams direct from your phone to your website, keeping</p>
            <p>full ownership of your videos.</p>
            <div class="Discover-section">
              <div class="row">
                <div class="col-md-4">
                  <div class="discover-content">
                    <figure>
                      <img src="{{ url('assets/images/home-new/free-livestream.png')}}">
                    </figure>
                    <strong>Free livestream plugins</strong>
                    <p>On WordPress, Drupal, Joomla and</p>
                    <p>more for easy integrations.</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="discover-button">
                    <a href="<?php// echo base_url('help'); ?>" class="btn-green">Discover More</a>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="discover-content">
                    <figure>
                      <a href="https://itunes.apple.com/mt/app/hapity/id1068976447?mt=8" target="_blank"><img src="{{ url('assets/images/home-new/apple-new.png')}}"></a>
                      <a href="https://play.google.com/store/apps/developer?id=hapity.com" target="_blank"><img src="{{ url('assets/images/home-new/play-store.png')}}"></a>
                    </figure>
                    <strong>Mobile apps</strong>
                    <p>Available from the App Store and</p>
                    <p>Google Play.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="broadcasting-Section">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="broadcasting-content">
              <h2>One-click broadcasting</h2>
              <img src="{{ url('assets/images/home-new/green-bar.png')}}">
              <p>Get instantly video streaming from your site with just one-click.
                Getting set up should be the least of your worries, which is why
                you can install, enable and start sharing your message with the
                world in under five minutes.
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mobile-section">
              <figure>
                <img src="{{ url('assets/images/home-new/mobiles.png')}}" class="img-responsive">
              </figure>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="reach-audience">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="audience-img">
              <figure>
                <img src="{{ url('assets/images/home-new/mans.png')}}" class="img-responsive">
              </figure>
            </div>
          </div>
          <div class="col-md-6">
            <div class="broadcasting-content">
              <h2>Reach every audience</h2>
              <img src="{{ url('assets/images/home-new/green-bar.png')}}">
              <p>No more scheduling headaches! One-click enables you to post
                your livestreams on Twitter and Facebook at the same time, with
                links back to your website. More traffic to your site, more leads
                and more sales for you.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="get-hapity">
      <div class="container">
        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-6">
            <div class="about-hapity">
              <div class="about-hapity-video">
                <div class="embed-responsive embed-responsive-16by9">
                  <video controls="" poster="{{ url('assets/images/home-new/hapity.png')}}" src="https://www.hapity.com/assets/videos/Hapity-Final-MP4.mp4" width="200" height="140"></video>
                </div>
              </div>
              <h2>Your Content. Your Rules.</h2>
              <p>When you live-stream directly from Facebook, Twitter or Snapchat, you
                don't own the content you've generated.
              </p>
              <p>Hapity enables you to stream directly from your own website while sharing across
                social media, so you keep ownership of everything you create. Hapity will
                even generate your own backups on your mobile for you.</p>
              <div class="hapity-button">
                <a href="<?php // echo base_url('help'); ?>">GET HAPITY NOW!</a>
              </div>
            </div>
          </div>
          <div class="col-md-3"></div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="hapity-logos">
        <div class="row">
          <div class="col-md-12 col-xs-12 col-sm-12">
            <div class="logos-heading">
              <h2>Get Hapity for iOS, Android, or on
                <br>
                WordPress.org.</h2>
            </div>
            <div class="logos">
              <ul>
                <li>
                  <a href="https://itunes.apple.com/mt/app/hapity/id1068976447?mt=8" target="_blank"><img src="{{ url('assets/images/home-new/apple.png')}}"  class="img-responsive">
                  </a>
                </li>
                <li>
                  <a href="https://play.google.com/store/apps/developer?id=hapity.com" target="_blank">
                    <img src="{{ url('assets/images/home-new/android.png')}}" class="img-responsive" >
                  </a>  
                </li>
                <li>
                  <a href="https://wordpress.org/plugins/wp-hapity/" target="_blank"><img src="{{ url('assets/images/home-new/wordpress.png')}}"  class="img-responsive"></a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="hapity-testimonial">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="carousel-mg text-center">
              <h3 class="text-center light-gray">What do customers think of Hapity?</h3>
              <h4>Testimonials & Social Proof</h3>
                <div class="owl-carousel owl-theme">
                  <div class="item text-center">

                    <p>“
As a blogger who frequently has spontaneous ideas, I find video as a convenient medium for me. Note that Hapity is my client, but I would never have a client on a product I don't love. It's so convenient for me to record a video on my phone wherever I happen to be and have a blog post on my site that is live and public. Of course, I have YouTube live, but that doesn't create a post for me on my WordPress site. Once I live broadcast and click end, this post is done. Hapity is a welcomed edition for my video marketing toolset."</p>
                    <p>Bridget Willard</p>
                   <!--  <p>Manager at ABCD</p> -->
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
