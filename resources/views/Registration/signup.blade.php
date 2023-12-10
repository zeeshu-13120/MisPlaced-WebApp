@extends('Layout.layout')
@section('title','Create New Account')
@section('style')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection

@section('content')
<div class=" section">
    <div class="container">
    <div class="row d-flex justify-content-center">
    <div class="col-lg-8   col-md-10  col-12">

      <form class="card  rounded-3 border  login-form mt-5 mb-5 needs-validation" action="{{ url('signup') }}" method="POST" novalidate>
        @csrf
        <div class="card-body ">
    <div class="title text-center">
    <h3>Create New Account</h3>
    </div>

    <div class="row">
      @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

      <div class="form-group col-md-6">
          <label for="f_name">First Name</label>
          <input class="form-control" type="text" id="f_name" name="first_name" placeholder="Enter First Name" value="{{ old('first_name') }}" required="">
          <div class="invalid-feedback">
              First Name is required.
          </div>
      </div>

      <div class="form-group col-md-6">
          <label for="l_name">Last Name</label>
          <input class="form-control" type="text" id="l_name" name="last_name" placeholder="Enter Last Name" value="{{ old('last_name') }}" required="">
          <div class="invalid-feedback">
              Last Name is required.
          </div>
      </div>
  </div>
  <div class="row">
      <div class="form-group col-md-6">
          <label for="email">Email</label>
          <input class="form-control" type="email" id="email" name="email" placeholder="Enter Email" value="{{ old('email') }}" required="">
          <div class="invalid-feedback">
              Email is required.
          </div>
      </div>
      <div class="form-group col-md-6">
          <label for="number">Phone</label>
          <input class="form-control" type="number" id="number" name="phone" placeholder="Enter Phone Number" value="{{ old('phone') }}" required="">
          <div class="invalid-feedback">
              Phone Number is required.
          </div>
      </div>
  </div>
  <div class="row">
      <div class="form-group col-md-6">
          <label for="password">Password</label>
          <input class="form-control" type="password" id="password1" name="password" placeholder="Enter Password" required="">
          <div class="invalid-feedback">
              Password is required.
          </div>
      </div>
      <div class="form-group col-md-6">
          <label for="password">Confirm Password</label>
          <input class="form-control" type="password" id="password2" name="password_confirmation" placeholder="Confirm Password" required>
          <div class="invalid-feedback">
              Confirm password not matched.
          </div>
      </div>
  </div>
    <div class="d-flex flex-wrap justify-content-between bottom-content">
    <div class="form-check">
    <input type="checkbox"  class="form-check-input width-auto" required id="exampleCheck1">
    <label class="form-check-label">Accept Terms and Conditions</label>
    </div>
    <a class="lost-pass text-primary" href="{{route('password.forgot')}}">Forgot password?</a>
    </div>
    <div class="button">
    <button class="btn btn-primary" type="submit">Create A New Account</button>
    </div>
    <p class="outer-link">Already have an account? <a class="text-primary" href="{{url('login')}}">Login here </a>
    </p>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>

@endsection

@section('script')
<script>
(() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
checkPasswordMatch();
      form.classList.add('was-validated')
    }, false)
  })
})()
function checkPasswordMatch() {
  const password1 = document.getElementById('password1');
  const password2 = document.getElementById('password2');

  if (password1.value !== password2.value) {
    password2.setCustomValidity('Passwords do not match.');
  } else {
    password2.setCustomValidity('');
  }
}

document.getElementById('password1').addEventListener('input', checkPasswordMatch);
document.getElementById('password2').addEventListener('input', checkPasswordMatch);

</script>
@endsection


