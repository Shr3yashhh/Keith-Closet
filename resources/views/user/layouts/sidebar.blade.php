@section('sidebar')
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <a href="{{url('/user-panel')}}" class="brand-link text-center">
      <h3>Patient Panel</h3>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          @if (Session::get('session_user')->avatar != null && Session::get('session_user')->role == 'user')
            <img src="{{url('user_avatar/'.Session::get('session_user')->avatar)}}" class="img-circle elevation-2" alt="{{Session::get('session_user')->name}}">
            @else
                <img src="{{url('user_avatar/default.jpg')}}" class="img-circle elevation-2" alt="{{Session::get('session_user')->name}}">

          @endif
        </div>
        <div class="info">
          <a href="{{route('users.edit',Session::get('session_user')->id)}}" class="d-block">{{Session::get('session_user')->name}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               {{-- <li class="nav-item menu-closed">
                <a href="#" class="nav-link active">
                  <i class="nav-icon fas fa-list"></i>
                  <p>
                    Grade
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="" class="nav-link ">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add Grade</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Manage Grade</p>
                    </a>
                  </li>


                </ul>
              </li> --}}

              <li class="nav-item menu-closed">
                <a href="{{url('user-panel')}}" class="nav-link active">
                  <i class="nav-icon fas fa-home"></i>
                  <p>
                    Dashboard
                  </p>
                </a>

              </li>
          <li class="nav-item menu-closed">
            <a href="{{ route('user.appointments.index') }}" class="nav-link active">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Request Appointment
              </p>
            </a>
          </li>




          {{-- <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Widgets
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>  --}}

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
