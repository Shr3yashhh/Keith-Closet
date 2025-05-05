@extends('admin.main')
@section('content')

<div class="d-flex justify-content-between">
    <div>
        <h4 class="mt-2"><i class="fa fa-users mr-1 "></i> List of Warehouse</h4><br>
        <h3>Number of Warehouse: {{ count($beds) }}</h3>
    </div>
    <div class="mt-2">
        <a href="{{ route("admin.beds.show") }}">
            <button class="btn-info">
                Add Warehouse
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

      @if ($beds)

          <table class="table table-bordered table-hover">
        <tr>
          <th>SN</th>
          <th>Name</th>
          <th>Code</th>
          <th>Address</th>
          <th>contact_number</th>
            <th>Action</th>
        </tr>
        @foreach ($beds as $key => $bed)
        <tr>
          <td>{{ ++$key }}</td>
          <td>{{ $bed->name }}</td>
          <td>{{ $bed->code }}</td>
          <td>{{ $bed->address }}</td>
          <td>{{ $bed->contact_number }}</td>


{{--            <td>--}}
{{--                @if($appointment->status == 'pending')--}}
{{--                    <button data-toggle="modal" data-target="#activeModal{{$key}}" class=" btn btn-sm btn-warning">{{ $appointment->status }}</button>--}}
{{--                @elseif($appointment->status == 'in_review')--}}
{{--                    <button data-toggle="modal" data-target="#activeModal{{$key}}" class="btn btn-sm btn-warning">{{ $appointment->status }}</button>--}}
{{--                @elseif($appointment->status == 'rejected')--}}
{{--                    <button data-toggle="modal" data-target="#activeModal{{$key}}" class="btn btn-sm btn-danger">{{ $appointment->status }}</button>--}}
{{--                @elseif($appointment->status == 'approved')--}}
{{--                    <button data-toggle="modal" data-target="#activeModal{{$key}}" class="btn btn-sm btn-success">{{ $appointment->status }}</button>--}}
{{--                @endif--}}

{{--                --}}{{-- modal start --}}
{{--                <div class="modal fade" id="activeModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--                    <div class="modal-dialog">--}}
{{--                        <div class="modal-content">--}}
{{--                            <div class="modal-header">--}}
{{--                                <h5 class="modal-title" id="exampleModalLabel">Choose status</h5>--}}
{{--                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                    <span aria-hidden="true">&times;</span>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                            <form action="{{route('admin.appointments.manage',$appointment->id)}}" method="POST">--}}
{{--                                <div class="modal-body">--}}
{{--                                    @csrf--}}
{{--                                    @method('PATCH')--}}
{{--                                    <div class="form-group">--}}

{{--                                        <select name="status" id="status" class="form-control">--}}
{{--                                            <option value="approved" @if ($appointment->status == \App\Enums\AppointmentStatusEnum::APPROVED->appointmentType()) selected @endif>Approved</option>--}}
{{--                                            <option value="rejected" @if ($appointment->status == \App\Enums\AppointmentStatusEnum::REJECTED->appointmentType()) selected @endif>Rejected</option>--}}
{{--                                            <option value="in_review" @if ($appointment->status == \App\Enums\AppointmentStatusEnum::IN_REVIEW->appointmentType()) selected @endif>In Review</option>--}}
{{--                                            <option value="pending" @if ($appointment->status == \App\Enums\AppointmentStatusEnum::PENDING->appointmentType()) selected @endif>Pending</option>--}}
{{--                                        </select>--}}

{{--                                    </div>--}}
{{--                                    <div class="modal-footer">--}}
{{--                                        <button type="submit" class="btn btn-primary">Change Status</button>--}}
{{--                                    </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                --}}{{-- modal end --}}
{{--            </td>--}}


          <td>
              <a href="{{ route("admin.beds.edit", $bed->id) }}" class="btn btn-sm btn-info"> <i class="fas fa-edit mr-1"></i> Edit</a>
              <a onclick="return confirm('Are you sure?')" href="{{ route('admin.beds.delete', $bed->id) }}" class="btn btn-danger btn-sm">Delete</a>
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
