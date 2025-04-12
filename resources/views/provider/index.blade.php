@extends('provider.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Doctor Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="row">



                        <div class="col-md-4">
                          <div class="card p-3">
                              <div class="cart-header">
                                  <h4 class="card-title">Today Appointment</h4>
                              </div>
                              <div class="card-body">
                                  <h1 class="card-text">{{ $request_count_today }}</h1>
                              </div>
                          </div>
                      </div>

                    </div>


                        {{-- chart data --}}
                        <div class="col-md-12">

                          <canvas id="canvas" height="280" width="600"></canvas>

                        </div>
                    </div>
            <script src="{{url('dist/js/Chart.min.js')}}"></script>

                      <script>
                        var year = <?php echo $year; ?>;

                        var request = <?php echo $request; ?>;
                        var barChartData = {
                            labels: year,
                            datasets: [
                            {
                                label: 'Request',
                                backgroundColor: "#4db2f6",
                                data: request
                            }
                            ]
                        };

                        window.onload = function() {
                            var ctx = document.getElementById("canvas").getContext("2d");
                            window.myBar = new Chart(ctx, {
                                type: 'bar',
                                data: barChartData,
                                options: {
                                    elements: {
                                        rectangle: {
                                            borderWidth: 2,
                                            borderColor: '#c1c1c1',
                                            borderSkipped: 'bottom'
                                        }
                                    },
                                    responsive: true,
                                    title: {
                                        display: true,
                                        text: 'Overview of all Requests'
                                    }
                                }
                            });
                        };
                    </script>
                @endsection
