@section('sidebar')
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/provider-panel')}}" class="brand-link text-center">
      <h3>Doctor Panel</h3>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          {{-- @if (Session::get('session_provider')->avatar != null && Session::get('session_provider')->role == 'provider')
            <img src="{{url('provider_avatar/'.Session::get('session_provider')->avatar)}}" class="img-circle elevation-2" alt="{{Session::get('session_provider')->name}}">
            @else
                <img src="{{url('provider_avatar/default.jpg')}}" class="img-circle elevation-2" alt="{{Session::get('session_provider')->name}}">

          @endif --}}
        </div>
        <div class="info">
          {{-- <a href="{{route('providers.edit',Session::get('session_provider')->id)}}" class="d-block">{{Session::get('session_provider')->name}}</a> --}}
        </div>
      </div>

      <!-- SidebarSearch Form -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->


               <li class="nav-item menu-closed">
                <a href="{{url('provider-panel')}}" class="nav-link active">
                  <i class="nav-icon fas fa-home"></i>
                  <p>
                    Dashboard
                  </p>
                </a>

              </li>
               <li class="nav-item menu-closed">
                <a href="{{route("provider.users")}}" class="nav-link active">
                  <i class="nav-icon fas fa-book"></i>
                  <p>
                    Manage Patient
                  </p>
                </a>

              </li>
            <li class="nav-item menu-closed">
                <a href="{{route('provider.appointments')}}" class="nav-link active">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Manage Appointment
                    </p>
                </a>

            </li>
            <li class="nav-item menu-closed">
                <a href="{{route('provider.tests')}}" class="nav-link active">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Manage Test
                    </p>
                </a>

            </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">

      <div class="container-fluid">
@endsection
