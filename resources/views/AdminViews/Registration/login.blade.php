<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

     <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />

  <!-- Icon File -->
  <link href="{{asset('bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('css/style.css')}}" rel="stylesheet">

</head>
<body>
    <main class="bg-primary ">
        <div class="container">

          <div class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
              <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">



                  <div class="card mb-3">

                    <div class="card-body">

                      <div class=" mb-1">
                        <div class="d-flex justify-content-center flex-wrap py-4">
                          <img src="{{asset('images/logo.png')}}" class="mx-auto" alt="Logo">
                          <a class="navbar-brand w-100 text-center  " href="/">
                            <h1><span class="text-secendory">Mis</span><span class="text-primary">Placed</span></h1>
                        </a>
                        </div><!-- End Logo -->
                        <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                      </div>
                      @if($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif

                      <form class="row g-3 needs-validation" action="{{ route('admin.login') }}" method="post" novalidate>
                        @csrf

                        <div class="col-12">
                          <label for="yourEmail" class="form-label">Email</label>
                          <div class="input-group has-validation">
                            <input required type="email" name="email" value="{{old('email')}}" class="form-control" id="yourEmail" required>
                            <div class="invalid-feedback">Please enter your Email.</div>
                          </div>
                        </div>

                        <div class="col-12">
                          <label for="yourPassword" class="form-label">Password</label>
                          <input required type="password" name="password" class="form-control" id="yourPassword" required>
                          <div class="invalid-feedback">Please enter your password!</div>
                        </div>

                        <div class="col-12">
                          <button class="btn btn-primary tex-white w-100" style="color:#fff" type="submit">Login</button>
                        </div>

                      </form>

                    </div>
                  </div>


                </div>

              </div>
            </div>

          </div>

        </div>
      </main>

      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

      <!-- Main JS File -->
      <script src="{{asset('js/main.js')}}"></script>
    </body>
    </html>