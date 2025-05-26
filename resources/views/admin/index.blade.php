@extends('admin.main')
@section('content')
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Admin Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card p-3">
                                <div class="cart-header">
                                    <form id="reportForm" method="GET" action="{{ route('admin.report.generate') }}">
                                        @csrf
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Generate Report
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><button class="dropdown-item" type="submit" name="type" value="daily">Daily</button></li>
                                                <li><button class="dropdown-item" type="submit" name="type" value="weekly">Weekly</button></li>
                                                <li><button class="dropdown-item" type="submit" name="type" value="monthly">Monthly</button></li>
                                            </ul>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="card p-3">
                                <div class="cart-header">
                                    <h4 class="card-title">Total Doctor</h4>
                                </div>
                                <div class="card-body">
                                    <h1 class="card-text">{{ $provider_count }}</h1>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
