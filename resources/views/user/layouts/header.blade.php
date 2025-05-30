@section('header')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{url('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">

  <link rel="stylesheet" href="{{url('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{url('dist/css/select2.min.css')}}">

  <link rel="stylesheet" href="{{url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <link rel="stylesheet" href="{{url('plugins/daterangepicker/daterangepicker.css')}}">
  <script src="{{url('plugins/jquery/jquery.min.js')}}"></script>
  <link rel="stylesheet" href="{{url('dist/css/mycustomBackendstyle.css')}}">



</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  {{-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{url('dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
  </div> --}}

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-info">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item  d-sm-inline-block">
        <a href="{{url('/')}}" style="text-decoration: none;" class="nav-link"><i class="fa fa-globe" ></i> Visit Site </a>

      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      {{-- <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> --}}

      <!-- Messages Dropdown Menu -->

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
{{--            @if($user_notification_count > 0)--}}
{{--          <span class="badge badge-warning navbar-badge">--}}
{{--          --}}
{{--              {{$user_notification_count}}--}}

{{--           --}}
{{--          </span>--}}
{{--           @endif--}}
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">
{{--            @if($user_notification_count > 0)--}}
{{--              {{$user_notification_count}} Notifications--}}
{{--            @else--}}
{{--              No Notifications--}}
{{--            @endif--}}
          </span>
          <div class="dropdown-divider"></div>

{{--          @if (count($user_notification_msg) > 0)--}}
{{--            @foreach ($user_notification_msg as $key => $notification)--}}
{{--              <a href="{{url('user-panel/request-history/'.Session::get('session_user')->id.'/'.$notification['notification_id'])}}" class="dropdown-item">--}}
{{--                <div class="media--}}
{{--                 @if ($notification['status'] == 'confirmed')--}}
{{--                  bg-success--}}
{{--                @elseif ($notification['status'] == 'rejected')--}}
{{--                  bg-danger--}}
{{--                @else--}}
{{--                  bg-warning--}}
{{--                @endif--}}
{{--                p-2">--}}
{{--                  <div class="media-body">--}}
{{--                    <h3 class="dropdown-item-title text-wrap">--}}
{{--                      {{$notification['message'][$key]}}--}}
{{--                    </h3>--}}
{{--                    <p class="text-sm "><i class="far fa-clock mr-1"></i>--}}
{{--                      <?php--}}

{{--                        $time_diff = strtotime(date('Y-m-d H:i:s')) - $notification['time_ago'][$key];--}}
{{--                        $days = floor($time_diff / (60 * 60 * 24));--}}
{{--                        $hours = floor(($time_diff - $days * 60 * 60 * 24) / (60 * 60));--}}
{{--                        $minutes = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);--}}
{{--                        $seconds = floor(($time_diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));--}}
{{--                        if($days > 0){--}}
{{--                            echo $days.' days ago';--}}
{{--                        }elseif($hours > 0){--}}
{{--                            echo $hours.' hours ago';--}}
{{--                        }elseif($minutes > 0){--}}
{{--                            echo $minutes.' minutes ago';--}}
{{--                        }elseif($seconds > 0){--}}
{{--                            echo $seconds.' seconds ago';--}}
{{--                        }--}}
{{--                        ?>--}}
{{--                      </p>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </a>--}}
{{--            @endforeach--}}

{{--          @else--}}

            <a href="#" class="dropdown-item">
              <div class="media">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    No Notifications
                  </h3>
                </div>
              </div>
            </a>
{{--          @endif--}}


{{--          <a href="{{route('user.request.history',Session::get('session_user')->id)}}" class="dropdown-item dropdown-footer">See All Notifications</a>--}}
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      {{-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>  --}}
      <li class="nav-item">
        <a class="btn btn-danger" onclick="return(confirm('Are you sure you want to log out?'))" data-widget="Log out" href="{{route('user.logout')}}" role="button">
          <i  class="fas fa-power-off"></i> Log out
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <style>
    .cat_description img{
        max-width: 30% !important;
        max-height: 100px !important;
    }
    .cat_description{
      width: auto;
    }
  </style>

@endsection
