@section('header')

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Keith's Closet</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{url('frontend/assets/img/favicon.png')}}" rel="icon">
  <link href="{{url('frontend/assets/img/favicon.png')}}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{url('frontend/assets/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{url('frontend/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{url('frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{url('frontend/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{url('frontend/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{url('frontend/assets/css/style.css')}}" rel="stylesheet">
  <link href="{{url('frontend/assets/css/myCustomStyle.css')}}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: SoftLand - v4.7.0
  * Template URL: https://bootstrapmade.com/softland-bootstrap-app-landing-page-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body  onload="initMap()">

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex justify-content-between align-items-center">

      <div class="logo brand-logo">

        <!-- Uncomment below if you prefer to use an image logo -->
      <a href="{{url('/')}}">
        <img src="{{url('frontend/assets/img/keithlogo.png')}}" alt="" style="filter:invert(80%);max-height:73px" class="img-fluid"></a>
     {{-- <a  class="brand-name" href="{{url('/')}}">HMS</a> --}}
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="active " href="{{url('/')}}">Home</a></li>
          <li><a href="{{url('/#services')}}">Services</a></li>
          <li><a href="{{url('/#services')}}">Donate</a></li>
          <li><a href="{{url('/#contact')}}">Contact Us</a></li>
          @guest
            <li><a href="{{ url('/admin-panel/login') }}">Login</a></li>
          @else
            <li><a href="{{ url('/admin-panel') }}">Dashboard</a></li>
          @endguest
          {{-- <li><a href="{{url('/admin-panel/login')}}">Login</a></li> --}}
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <main id="main">

@endsection
