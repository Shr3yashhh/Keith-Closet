@extends('admin.main')
@section('content')
{{--    @dd($medicines)--}}

<div class="d-flex justify-content-between">
    <div>
        <h4 class="mt-2"><i class="fa fa-users mr-1 "></i> List of Appointment</h4>
    </div>
    <div class="mt-2">
        <a href="/provider-panel/providers">
            <button class="btn-info">
                Add Appointment
            </button>
        </a>
    </div>
</div>
@if (Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif
@if (Session::has('error'))
<div class="alert alert-danger">
    {{ Session::get('error') }}
</div>
@endif
<hr>
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
{{--      <a class="nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-active" role="tab" aria-controls="nav-profile" aria-selected="false">Active</a>  --}}
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade  show active" id="nav-active" role="tabpanel" aria-labelledby="nav-home-tab">

      @if ($medicines)

          <table class="table table-bordered table-hover">
        <tr>
          <th>SN</th>
            <th>Name</th>
            <th>Patient Name</th>
          <th>Doctor Name</th>
            <th>Action</th>
        </tr>
        @foreach ($medicines as $key => $medicine)
        <tr>
{{--            @dd($medicine)--}}
          <td>{{ ++$key }}</td>
            <td>{{ $medicine->name }}</td>
            <td>{{ $medicine->patient->name }}</td>
          <td>{{ $medicine->doctor->name }}</td>


          <td>
            <a onclick="return confirm('Are you sure?')" href="{{ route('admin.users.soft_delete', $medicine->id) }}" class="btn btn-danger btn-sm">Delete</a>
          </td>
        </tr>
        @endforeach
      </table>
      @else
      <p class="mt-3" style="color: red">
        There are no active users.
      </p>

      @endif
    </div>
  </div>


@endsection
