@extends('layouts.app')

@push('css')
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
@endpush

@section('content')
	<!--include hapity header file-->

<div class="profile-page new_design">
  <div class="section-main">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          @if (Session::has('flash_message'))
         
              <div class="alert alert-success">{{ Session::get('flash_message') }}</div>
          @endif
          @if(Session::has('flash_message_delete'))

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
              <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
              
              <p>&nbsp;</p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
            	<input type="submit" value="SubmitContactQuery" id="SubmitContactQuery">

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


</div>
<div class="clear"> <p><br><br></p></div>

@endsection


@push('script')
  <script src="https://www.google.com/recaptcha/api.js?render={{env('GOOGLE_RECAPTCHA_KEY')}}" async defer></script>
@endpush



