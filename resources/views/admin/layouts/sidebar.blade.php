@section('sidebar')
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/admin-panel')}}" class="brand-link text-center">
      <a href="{{url('/')}}">
        <img src="{{url('frontend/assets/img/keithlogo.png')}}" alt="" style="filter:invert(80%);max-height:90px" class="img-fluid"></a>
      {{-- <h3>Keith Closet</h3> --}}
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          @if (Session::get('session_admin')->avatar != null && Session::get('session_admin')->role == 'admin')
            <img src="{{url('admin_avatar/'.Session::get('session_admin')->avatar)}}" class="img-circle elevation-2" alt="{{Session::get('session_admin')->name}}">
            @else
                <img src="{{url('admin_avatar/default.jpg')}}" class="img-circle elevation-2" alt="{{Session::get('session_admin')->name}}">

          @endif
        </div>
        <div class="info">
          <a href="{{route('admins.edit',Session::get('session_admin')->id)}}" class="d-block">{{Session::get('session_admin')->name}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
               <li class="nav-item menu-closed">
                <a href="{{url('admin-panel')}}" class="nav-link active">
                  <i class="nav-icon fas fa-home"></i>
                  <p>
                    Dashboard
                  </p>
                </a>

              </li>
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Manage
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              {{-- <li class="nav-item">
                <a href="{{route('admin.users')}}" class="nav-link ">
                  <i class="far fa-user nav-icon"></i>
                  <p>Manage Patience</p>
                </a>
              </li> --}}
              <li class="nav-item">
                <a href="{{route('admin.providers')}}" class="nav-link">
                  <i class="far fa-user-circle nav-icon"></i>
                  <p>Admins</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.appointments')}}" class="nav-link">
                    <i class="far fa-user-circle nav-icon"></i>
                    <p>Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.beds')}}" class="nav-link">
                    <i class="far fa-user-circle nav-icon"></i>
                    <p>Warehouse</p>
                </a>
              </li>
                <li class="nav-item">
                    <a href="{{route('admin.tests')}}" class="nav-link">
                        <i class="far fa-user-circle nav-icon"></i>
                        <p>Stock Management</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.orders')}}" class="nav-link">
                        <i class="far fa-user-circle nav-icon"></i>
                        <p>Orders</p>
                    </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('admin.donations')}}" class="nav-link">
                      <i class="far fa-user-circle nav-icon"></i>
                      <p>Donation</p>
                  </a>
              </li>
            </ul>
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
