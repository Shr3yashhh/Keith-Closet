@extends('admin.main')
@section('content')
{{--    @dd($medicines)--}}

<div class="d-flex justify-content-between">
    <div>
        <h4 class="mt-2"><i class="fa fa-users mr-1 "></i> List of Orders</h4>
    </div>
    <div class="mt-2">
        <a href="{{ route('admin.orders.show') }}">
            <button class="btn-info">
                Add Orders
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

      @if ($orders->count() > 0)

          <table class="table table-bordered table-hover">
        <tr>
          <th>SN</th>
            <th>Sender Warehouse</th>
            <th>Receiver Warehouse</th>
          <th>Quantity</th>
          <th>status</th>
            <th>Action</th>
        </tr>
        @foreach ($orders as $key => $order)
        <tr>
          <td>{{ ++$key }}</td>
            <td>{{ $order->senderWarehouse->name }}</td>
            <td>{{ $order->receiverWarehouse->name }}</td>
          <td>{{ $order->quantity }}</td>
          <td>{{ $order->status }}</td>


          <td>
            {{-- <a onclick="return confirm('Are you sure?')" href="{{ route('admin.users.soft_delete', $order->id) }}" class="btn btn-danger btn-sm">Delete</a> --}}
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
