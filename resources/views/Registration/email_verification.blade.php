@extends('Layout.layout')
@section('title','Email Verification')
@section('style')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection

@section('content')
<div class="section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">

                <form class="card rounded-3 border login-form mt-5 mb-5" action="{{ url('verify_email') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" id="timestamp" value="{{ time() }}">
                    <div class="card-body">
                        <div class="title text-center">
                            <h3>Email Verification</h3>
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
                            @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="alert alert-success">
                          Verification Email Has Been Sent
                        </div>

                        </div>
                      <form action="{{route('verification.send')}}" method="post">
                        <p class="outer-link">Didn't receive the code? <button class="btn btn-primary" type="submit" id="resend-link">Resend</button>
                            <span id="countdown" class="text-muted"></span>
                        </p>
                    </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
// ...

// Countdown timer
const resendLink = document.getElementById('resend-link');
const countdownElement = document.getElementById('countdown');
const timestampElement = document.getElementById('timestamp');
const waitTime = 60; // Time to wait in seconds

function updateCountdown() {
  const currentTime = Math.floor(Date.now() / 1000);
  const sentTime = parseInt(timestampElement.value);
  const elapsedTime = currentTime - sentTime;
  const remainingTime = waitTime - elapsedTime;

  if (remainingTime > 0) {
    resendLink.style.pointerEvents = 'none';
    resendLink.style.opacity = '0.5';
    countdownElement.innerHTML = `(${remainingTime} seconds)`;
    setTimeout(updateCountdown, 1000);
  } else {
    resendLink.style.pointerEvents = 'auto';
    resendLink.style.opacity = '1';
    countdownElement.innerHTML = '';
  }
}

updateCountdown();

</script>
@endsection
