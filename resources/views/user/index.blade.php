@extends('user.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Patient Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                          <div class="card p-3">
                              <div class="cart-header">
                                  <h4 class="card-title">Today's Requests</h4>
                              </div>
                              <div class="card-body">
                                  <h1 class="card-text">{{ $request_count_today }}</h1>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>


                        {{-- chart data --}}
                        <div class="col-md-12">

                          <canvas id="canvas" height="280" width="600"></canvas>

                        </div>
                    </div>
                @endsection
