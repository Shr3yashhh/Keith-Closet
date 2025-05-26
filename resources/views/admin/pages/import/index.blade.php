@extends('admin.main')
@section('content')
{{--    @dd($medicines)--}}

<div class="d-flex justify-content-between">
    <div>
        <h4 class="mt-2"><i class="fa fa-users mr-1 "></i> List of Import</h4>
    </div>
    <div class="mt-2">
        <a href="{{ route('admin.import.show') }}">
            <button class="btn-info">
                Add Import
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

      @if ($import->count() > 0)

          <table class="table table-bordered table-hover">
        <tr>
          <th>SN</th>
          <th>type</th>
          <th>Total Row</th>
          <th>Updated Row</th>
          <th>status</th>
        </tr>

        @foreach ($import as $key => $imp)
        {{-- @dd($imp) --}}
        <tr>
          <td>{{ ++$key }}</td>
          <td>{{ $imp->type }}</td>
          <td>{{ $imp->total_rows }}</td>
          <td>{{ $imp->updated_rows }}</td>
          <td>{{ $imp->status }}</td>


          {{-- <td>
            @if ($statusConfig)
                <span class="text-{{ $statusConfig['color'] }}">
                    {{ $statusConfig['label'] }}
                </span>
            @else
                {{ ucfirst($order->status) }}
            @endif
        </td> --}}

          <td>
            {{-- <a href="{{ route("admin.donations.edit", $order->id) }}" class="btn btn-sm btn-info"> <i class="fas fa-eye mr-1"></i> View</a>
            <a onclick="return confirm('Are you sure?')" href="{{ route('admin.donations.delete', $order->id) }}" class="btn btn-danger btn-sm">Delete</a> --}}
          </td>
        </tr>
        @endforeach
      </table>
      @else
      <p class="mt-3" style="color: red">
        No any Donation found.
      </p>

      @endif
    </div>
  </div>


@endsection
