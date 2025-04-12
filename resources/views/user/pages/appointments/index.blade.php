@extends('user.main')
@section('content')
    <div class="d-flex justify-content-between">
        <div>
            <h4 class="mt-2"><i class="fa fa-users mr-1 "></i> List of Appointment</h4>
        </div>
        <div class="mt-2">
            <a href="{{ route("user.appointment.show") }}">
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
            <a class="nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-profile" aria-selected="false">All</a>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-profile-tab">
            @if (count($appointments) > 0)
                <table class="table table-bordered table-hover">
                    <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Doctor</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($appointments as $key => $appointment)
                    <tr>
                      <td>{{ ++$key }}</td>
                      <td>{{ $appointment->patient->name }}</td>
                      <td>{{ $appointment->doctor->name }}</td>
                      <td>{{ $appointment->description }}</td>
                        <td>{{ $appointment->date }}</td>


                        <td>
                            <button data-toggle="modal" data-target="#activeModal{{$key}}" class=" btn btn-sm btn-warning">{{ $appointment->status }}</button>
                        </td>
                      <td>
                          @if($appointment->status === "pending")
                              <a href="{{ route("user.appointment.edit", $appointment->id) }}" class="btn btn-sm btn-info"> <i class="fas fa-edit mr-1"></i> Edit</a>
                          @endif
                          <a onclick="return confirm('Are you sure?')" href="{{ route('user.appointments.delete', $appointment->id) }}" class="btn btn-danger btn-sm">Delete</a>
                      </td>
                    </tr>
                    @endforeach
                </table>
            @endif
        </div>

    </div>


@endsection
