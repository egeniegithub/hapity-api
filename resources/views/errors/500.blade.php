{{-- @extends('errors::minimal') --}}

@extends('layouts.app')
@section('content')
<div class="profile-page new_design">
    <div class="section-main">
      <div class="container">        
        <div class="about-ContactForm-wrapepr-new" style="padding: 50px; margin: 150px auto;">
          <h2 class="text-center">Opps... Something went wrong!</h2>          
        </div>
      </div>
    </div>
  
  
  </div>
  <div class="clear"> <p><br><br></p></div>
  @endsection

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))
