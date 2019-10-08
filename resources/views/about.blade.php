@extends('layouts.app')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@section('content')
	<!--include hapity header file-->

<div class="profile-page new_design">
  <div class="section-main">
    <div class="container">
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
      <div class="about-ContactForm-wrapepr-new">
        <h2>SEND US A MESSAGE</h2>
        <form  method="post" action="{{route('contact.us.send.email')}}">
        	@csrf
          <div class="form-group row">
            <div class="col-xs-6">
              <label for="name">Name</label>
              <input class="form-control" id="name" name="name" type="text" required/>
            </div>
            <div class="col-xs-6">
              <label for="Email">Email</label>
              <input class="form-control" id="Email" name="email" type="email" required/>
            </div>
            <div class="col-xs-12">
              <label for="Message">Message</label>
              <textarea class="form-control" id="Message" name="message" required></textarea>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
              <label for="g-recaptcha">Captcha</label>
              <div class="g-recaptcha" data-sitekey="6Lf9xLoUAAAAANkPex8syVDugeSH73EJeeTeqByn"></div>
              <?php // echo $recaptchaScriptTag; ?>
              <?php // echo $recaptcha; ?>
              <p>&nbsp;</p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
            	<input type="submit" value="SubmitContactQuery" id="SubmitContactQuery">
              {{-- <input type="submit" value="SUBMIT" id="SubmitContactQuery" name="SubmitContactQuery"/> --}}
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


</div>
<div class="clear"> <p><br><br></p></div>

@endsection




<style>
.navbar-custom .navbar-toggle {
    background: #fff !important;
    margin-top: 30px;
}
.navbar-custom .navbar-toggle .icon-bar {
    background: #391751;
}
.navbar-custom .navbar-toggle .icon-bar {
    display: block;
    width: 22px;
    height: 2px;
    border-radius: 1px;
}
</style>