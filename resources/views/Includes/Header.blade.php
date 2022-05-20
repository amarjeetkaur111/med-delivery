
<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Medication Delivery</title>
  <!-- Font Awesome  -->
  <link rel="stylesheet" href="{{ asset('css/font.css') }}">
  <!-- Bootstrap core CSS  -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <!-- Material Design Bootstrap  -->
  <link rel="stylesheet" href="{{ asset('css/mdb.min.css') }}">
  <!-- DataTables.net  -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/addons/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/addons/datatables-select.min.css') }}">
  <!-- Custom CSS  -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
  <!----------------------Jquery------------------->
  <script src="{{asset('js/jquery-3.5.1.min.js')}}"></script>
  <!-- Your custom styles (optional)  -->
  <script>
    var socket_url ='<?= config('app.socket_url')?>'; 
    </script>
</head>

<body class="fixed-sn">
  <!-- Main Navigation  -->
  <header>
  <!-- Sidebar navigation  -->
  @include('Includes.Sidebar')
  <!-- Sidebar navigation  -->
    <!-- Navbar  -->
    <nav class="navbar fixed-top navbar-expand-lg double-nav pt-1" style="padding-left:7rem;">
      <!-- SideNav slide-out button  -->
      <div class="float-left">
        <a href="#" data-activates="slide-out" class="button-collapse" id="sidebar"><i class="fas fa-bars"></i></a>
      </div>
      <!-- Breadcrumb  -->
    <ul class="nav main-nav md-pills nav-justified pills-deep-blue pl-5">
          <li class="nav-item mb-n3">
                <a href="{{route('visits')}}" class="nav-link nav-bar img mb-n3"><img src="{{asset('img/Rx360Logo.png')}}"></a>
              <label class="small mt-1">Medication Delivery</label>
          </li>
          <li class="nav-item ">
            <a class="nav-link nav-bar {{ (Request::routeIs('NewOrder') || Request::routeIs('NewSchedule')) ? 'active' : '' }}" href="{{route('NewOrder')}}"><i class="icon-item fas fa-box fa-lg" role="button" data-prefix="fas" data-id="box" data-unicode="f466" data-mdb-original-title="" title=""></i> New Orders</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link nav-bar {{ Request::routeIs('visits') ? 'active' : '' }}" href="{{route('visits')}}"><i class="icon-item fas fa-box fa-lg" role="button" data-prefix="fas" data-id="box" data-unicode="f466" data-mdb-original-title="" title=""></i> Orders</a>
          </li>
          @if(Session::get('usertype') != "Driver")
          <li class="nav-item">
            <a class="nav-link nav-bar {{ Request::routeIs('customers') ? 'active' : '' }}" href="{{route('customers')}}"><i class="icon-item fas fa-users fa-lg" role="button" data-prefix="fas" data-id="users" data-unicode="f0c0" data-mdb-original-title="" title=""></i> Customers</a>
          </li>
          @endif
          @if(Session::get('usertype') == "Admin")
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false"><i class="icon-item fas fa-palette fa-lg" data-prefix="fas" data-id="palette" data-unicode="f53f" data-mdb-original-title="" title=""></i>
              <span class="d-none d-md-inline-block">Administration</span>
            </a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="" style="width:12rem;">
              <a class="dropdown-item {{ Request::routeIs('goods') ? 'active' : '' }}" href="{{route('goods')}}">Goods</a>
              <a class="dropdown-item {{ Request::routeIs('tags') ? 'active' : '' }}" href="{{route('tags')}}">Tags</a>
              <a class="dropdown-item {{ Request::routeIs('users') ? 'active' : '' }}" href="{{route('users')}}">Users</a>
              <a class="dropdown-item {{ Request::routeIs('pharmacies') ? 'active' : '' }}" href="{{route('pharmacies')}}">Pharmacies</a>
            </div>
          </li>
          @endif
          @if(Session::get('usertype') != "Driver")
          <li class="nav-item">
            <a class="nav-link nav-bar {{ Request::routeIs('reports') ? 'active' : '' }}" href="{{route('reports')}}"><i class="icon-item fas fa-file-contract fa-lg" role="button" data-prefix="fas" data-id="file-contract" data-unicode="f56c" data-mdb-original-title="" title=""></i>  Reports</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-bar {{ Request::routeIs('map') ? 'active' : '' }}" href="{{route('map')}}"><i class="icon-item fas fa-map-marker-alt fa-lg" role="button" data-prefix="fas" data-id="map-marker-alt" data-unicode="f56c" data-mdb-original-title="" title=""></i>  Track Order</a>
          </li>
          @endif
        </ul>
      <!-- Navbar links  -->
      <ul class="nav navbar-nav nav-flex-icons ml-auto">

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle waves-effect" href="#" id="userDropdown" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user" ></i> <span class="profile clearfix d-none d-sm-inline-block">Profile</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">

            <!-- <a class="dropdown-item" href="#">My account</a> -->
            <a class="dropdown-item" href="{{route('logout')}}">Log Out</a>
            <div class="card">

              <div class="card-body mt-0 text-center">
                <!-- Name -->
                <h4 class=" font-weight-bold mb-0"><strong>{{ucfirst(session('username'))}}</strong></h4>
                <h6 class="font-weight-bold cyan-text mb-0">{{session('usertype')}}</h6>
                <p class=" text-muted mb-0 mt-0">{{session('user')}}</p>
              </div>
            </div>
          </div>
        </li>

      </ul>
      <!-- Navbar links  -->

    </nav>
    <!-- Navbar  -->
    <!-- Alert Messages  -->

    <div class="alert-messages">
      @if(session()->has('msg'))
      <div class="alert alert-info alert-dismissible fade show" role="alert" >
        {{session('msg')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if(session()->has('errormsg'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{session('errormsg')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if(session()->has('successmsg'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session('successmsg')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
    </div>
  </header>
