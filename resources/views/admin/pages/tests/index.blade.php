@extends('admin.main')
@section('content')
{{--    @dd($appointments)--}}

<div class="d-flex justify-content-between">
    <div>
        <h4 class="mt-2"><i class="fa fa-users mr-1 "></i> List of products stocks in warehouses</h4>
    </div>
    <div class="mt-2">
        <a href="{{ route("admin.tests.show") }}">
            <button class="btn-info">
                Add Products Stocks
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


  {{-- @section('content') --}}
    <div class="container">

        {{-- Filter Form --}}
        <div class="card mb-4">
            <div class="card-body">
                <form id="filter-form">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="product_id" class="form-control">
                                <option value="">-- Select Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <select name="warehouse_id" class="form-control">
                                <option value="">-- Select Warehouse --</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Results --}}
        {{-- <div id="test-results">
            @include('admin.pages.tests.partials.test_table', ['tests' => $tests])
        </div> --}}

    </div>
{{-- @endsection --}}


  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade  show active" id="nav-active" role="tabpanel" aria-labelledby="nav-home-tab">

      @if ($tests)


          <table class="table table-bordered table-hover">
        <tr>
          <th>SN</th>
          <th>Product</th>
          <th>Warehouse</th>
          <th>Bin Location</th>
          <th>quantity</th>
            <th>Action</th>
        </tr>
        @foreach ($tests as $key => $test)
        <tr>
          <td>{{ ++$key }}</td>
          <td>{{ $test?->product?->name }}</td>
          <td>{{ $test?->warehouse->name }}</td>
          <td>{{ $test?->bin_location }}</td>
          <td>{{ $test->quantity }}</td>


             {{-- <td> --}}
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
              <a href="{{ route("admin.tests.edit", $test->id) }}" class="btn btn-sm btn-info"> <i class="fas fa-edit mr-1"></i> Edit</a>
              <a onclick="return confirm('Are you sure?')" href="{{ route('admin.tests.delete', $test->id) }}" class="btn btn-danger btn-sm">Delete</a>
              {{-- <a href="{{ route('admin.test.report', $test->id) }}" class="btn btn-success btn-sm">PDF</a> --}}
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


<script>
  $(document).ready(function () {
      $('#filter-form').on('submit', function (e) {
          e.preventDefault();

          $.ajax({
              url: "{{ route('admin.tests') }}",
              type: 'GET',
              data: $(this).serialize(),
              success: function (data) {
                  $('#test-results').html(data);
              },
              error: function () {
                  alert('Failed to load filtered data.');
              }
          });
      });

      // Reset button functionality
      // $('#resetBtn').on('click', function () {
      //       // Clear all form inputs
      //       $('#filter-form')[0].reset();

      //       // Trigger form submit after resetting
      //       $('#filter-form').submit();
      //   });
      document.addEventListener("DOMContentLoaded", function () {
          const resetBtn = document.getElementById("resetBtn");

          resetBtn.addEventListener("click", function () {
              // Clear select inputs manually
              document.querySelector('select[name="product_id"]').value = '';
              document.querySelector('select[name="warehouse_id"]').value = '';
          });
          const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
      });
  });
</script>