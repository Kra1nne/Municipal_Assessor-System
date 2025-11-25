@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('page-script')
@vite('resources/assets/js/login.js')
@endsection

@section('content')
<div class="container-fluid position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6 mx-4">

      <!-- Login -->
      <div class="card p-3">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="{{url('/')}}" class="app-brand-link gap-3">
            <span class="app-brand-logo demo">@include('_partials.macros',["height"=>100,"withbg"=>'fill: #fff;'])</span>
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-1">
          <h4 class="mb-1">Welcome to {{config('variables.templateName')}}!</h4>

          <form id="formAuthentication" class="mb-5">
            @csrf
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" id="email" name="email_username" placeholder="Enter your email or username" autofocus>
              <label for="email">Email or Username</label>
            </div>
            <div class="mb-5">
              <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <label for="password">Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line ri-20px"></i></span>
                </div>
              </div>
            </div>
          </form>
          <div class="" id="loginBtn">
            <button class="btn btn-primary d-grid w-100" type="submit">login</button>
          </div>
        <div>
          <div class="d-flex justify-content-center align-items-center my-3">
            <hr class="flex-grow-1">
            <span class="mx-3 text-black">SIGN IN WITH</span>
            <hr class="flex-grow-1">
          </div>
          <div class="d-flex justify-content-center mt-3 gap-3">
              <a href="{{ route('auth.google.redirect') }}" class="btn btn-primary border rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                <i class="ri-google-line"></i>
              </a>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
@if(session('show_modal'))
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: 'error',
        title: 'Login Error',
        text: 'Invalid Gmail Login',
        confirmButtonText: 'Okay',
    });
});
</script>
@endif
@endsection
