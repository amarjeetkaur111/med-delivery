<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Medication Delivery Login</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('css/font.css') }}">
  <!-- Bootstrap core CSS -->
  <link href="{{ asset('css/bootstrap.min.css') }} " rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/loginstyle.css') }}">  
</head>
<body class="login-page">
  <!-- Main Navigation -->
  <header>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar">
      <div class="container">
          <img src="{{asset('img/Rx360Logo.png')}}">
          <div class="collapse navbar-collapse" id="navbarSupportedContent-7">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active mb-n3">
              <label>&nbsp;Medication Delivery </label>
            </li>
          </ul>          
        </div>
      </div>
      </div>
    </nav>
    <!-- Navbar -->
    <!-- Intro Section -->
    <section class="view intro-2">
      <div class="mask rgba-stylish-strong h-100 d-flex justify-content-center align-items-center">
        <div class="container">
          <div class="row">
            <div class="col-xl-5 col-lg-6 col-md-10 col-sm-12 mx-auto mt-5">
              <!-- Form with header -->
              <div class="card wow fadeIn" data-wow-delay="0.3s">
                <div class="card-body">
                  <!-- Header -->
                  <div class="form-header blue-gradient">
                    <h3 class="font-weight-500 my-2 py-1">Medication Delivery</h3>
                  </div>
                  <!-- Body -->
                    <div class="text-center">
                      @if(Session::has('message'))
                        <div class="alert alert-info">{{Session::get('message')}}</div>
                      @endif
                    </div>
                    <form method="post" action="{{route('UserLogin')}}">
                    @csrf
                      <div class="md-form">
                        <i class="fas fa-user prefix  grey-text"></i>
                        <input type="text" id="orangeForm-email" name="email" class="form-control" placeholder="Email" required >
                        <label for="orangeForm-email"></label>
                      </div>

                      <div class="md-form mb-5">
                        <i class="fas fa-lock prefix  grey-text"></i>
                        <input type="password" id="orangeForm-pass" name="password" class="form-control" placeholder="Password" required>
                        <label for="orangeForm-pass"></label>
                      </div>

                      <div class="text-center">
                        <input type="submit" name="submit" value="Login" class="btn blue-gradient btn-lg">
                        <hr class="mt-4">
                        <a href="{{route('forgot.password.get')}}">Forgot Password?</a>                
                      </div>
                      <div class="text-center">
                      @if(Session::has('login_data'))
                        <div class="alert alert-info">{{Session::get('login_data')}}</div>
                      @endif
                      @if($errors->has('UserEmail'))
                        <span class="text-danger">{{"Error"}}</span>
                      @endif
                      @if($errors->has('Password'))
                        <span class="text-danger">{{"Error"}}</span>
                      @endif
                    </div>
                    </form>
                </div>
              </div>
              <!-- Form with header -->
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Intro Section -->
  </header>
  <!-- Main Navigation -->

  <!-- SCRIPTS -->
  <!-- JQuery -->
  <script type="text/javascript" src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="{{ asset('js/mdb.js') }} "></script>

  <!-- Custom scripts -->
  <script>

    new WOW().init();

  </script>

</body>

</html>
