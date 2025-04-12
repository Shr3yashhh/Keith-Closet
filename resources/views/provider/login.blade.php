<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="{{url('plugins/fontawesome-free/css/all.min.css')}}">
        <link rel="stylesheet" href="{{url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">

    <section class="text-center text-lg-start">

        <style>
            .cascading-right {
                margin-right: -50px;
            }

            @media (max-width: 991.98px) {
                .cascading-right {
                    margin-right: 0;
                }
            }

            body {
                background-color: #f5f5f5;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                height: 100vh;
            }
        </style>
</head>

<body>


    <!-- Jumbotron -->
    <div class="container py-4">
        <div class="row g-0 align-items-center">
            <div class="col-lg-6 offset-lg-3  mb-5 mb-lg-0">
                <div class="card cascading-right"
                    style="
              background: hsla(0, 0%, 100%, 0.55);
              backdrop-filter: blur(30px);
              ">
                    <div class="card-body p-5 shadow-5">
                        @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{Session::get('success')}}
                        </div>

                            
                        @endif
                        @if (Session::has('error'))
                        <div class="alert alert-danger">
                            {{Session::get('error')}}
                        </div>
                            
                        @endif
                        <a href="{{url('/')}}" style="text-decoration: none;"><i class="fa fa-arrow-left" ></i> Goto Site</a> 
                        <h2 class="fw-bold mb-5 text-center"> <i class="fa fa-lock"></i> Doctor Login </h2>
                        <p id="status"></p>
                        <form action="{{route('provider.verify')}}" method="POST">


                            @csrf
                            <input type="hidden" name="current_latitude" id="lat">
                            <input type="hidden" name="current_longitude" id="lng">
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <label class="form-label">Email</label>

                                <input type="text" name="email" class="form-control" />
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <label class="form-label">Password</label>

                                <input type="password" name="password" class="form-control" />
                            </div>



                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-block mb-4">
                                Proceed to Login
                            </button>
                            <div class="text-center">
                                <small class="text-muted">
                                    Don't have an account? <a href="{{route('providers.create')}}">Sign up Now</a>
                                </small>
                            </div>


                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!-- Jumbotron -->
    </section>
    <!-- Section: Design Block -->
<script>
    
    window.onload = function() {
        var status = document.getElementById('status');
        var lat = document.getElementById('lat');
        var lng = document.getElementById('lng');

        function success(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            status.textContent = '';
            lat.value = latitude;
            lng.value = longitude;
                        
        }

        function error() {
            status.textContent = 'Unable to retrieve your location';
        }

        if (!navigator.geolocation) {
            status.textContent = 'Geolocation is not supported by your browser';
        } else {
            status.textContent = 'Locating your location…';
            navigator.geolocation.getCurrentPosition(success, error);
        }

    }
    
</script>

</body>

</html>
