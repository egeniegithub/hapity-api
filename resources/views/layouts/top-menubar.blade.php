<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right nav-color">

      <li><a href="{{route('home')}}">Home</a></li>
      @if(Auth::check())
        @if(Auth::user()->hasRole(SUPER_ADMIN_ROLE_ID))
          <li><a href="{{route('admin.dashboard')}}" class="sign_in">Admin</a></li>
        @else 
          <li><a href="{{route('user.dashboard')}}" class="sign_in">dashboard</a></li>
          <li><a href="{{route('settings')}}" class="sign_in">settings</a></li>
        @endif
      @else
      <li><a href="{{route('login')}}">Login</a></li>
      <li><a href="{{route('register')}}">Register</a></li>
      @endif
      <!-- <li><a href="url('about'); ?>">Who we are</a></li> -->
      <li><a href="http://blog.hapity.com/">Blog</a></li>
      <li><a href="{{route('help')}}">Help</a></li>
      <li><a href="{{route('about')}}">Contact</a></li>

      @if(Auth::check())
      <li><a href="{{route('logout')}}" class="sign_in">Logout</a></li>
      @endif

    </ul>
  </div><!-- /.navbar-collapse -->