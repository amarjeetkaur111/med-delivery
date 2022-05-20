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
<section class="view intro-2">
  <div class="mask rgba-stylish-strong h-100 d-flex justify-content-center align-items-center">  
    <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Reset Password</div>
          <div class="card-body">

            @if (Session::has('message'))
                 <div class="alert alert-success" role="alert">
                    {{ Session::get('message') }}
                </div>
            @endif

              <form action="{{ route('forgot.password.post') }}" method="POST">
                  @csrf
                  <div class="form-group row">
                      <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                      <div class="col-md-6">
                          <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                          @if ($errors->has('email'))
                              <span class="text-danger">{{ $errors->first('email') }}</span>
                          @endif
                      </div>
                  </div>
                  <div class="col-md-6 offset-md-4">
                      <button type="submit" class="btn btn-primary">
                          Send Password Reset Link
                      </button>
                  </div>
              </form>
                
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
</body>

