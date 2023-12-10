@extends('Layout.layout')
@section('title','Forgot Password')
@section('style')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection

@section('content')
<div class="section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">

                <form class="card rounded-3 border login-form mt-5 mb-5 needs-validation" action="{{ url('forgot_password') }}" method="POST" novalidate>
                    @csrf
                    <div class="card-body">
                        <div class="title text-center">
                            <h3>Forgot Password</h3>
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
                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif
                            <div class="form-group col-12">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" name="email" id="email" placeholder="Enter Your Email Address" required>
                                <div class="invalid-feedback">
                                    Email is required.
                                </div>
                            </div>
                        </div>
                        <div class="button">
                            <button class="btn btn-primary" type="submit">Send Password Reset Link</button>
                        </div>
                        <p class="outer-link">Remembered your password? <a href="{{url('login')}}">Login</a>
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

  const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
</script>
@endsection
