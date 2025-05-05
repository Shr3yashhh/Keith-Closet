<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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

<body style="background-color: #f8f9fa; padding: 30px;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4 text-primary">Reset Your Password</h2>

                        <p class="text-muted mb-4">
                            You recently requested to reset your password. Click the button below to proceed:
                        </p>

                        <div class="text-center mb-4">
                            <a href="{{ $details['link'] }}" class="btn btn-primary px-4 py-2">
                                Reset Password
                            </a>
                        </div>

                        <p class="text-muted small">
                            If you did not request a password reset, you can safely ignore this email.
                        </p>

                        <hr class="my-4">

                        <p class="text-center text-muted small mb-0">
                            &copy; {{ date('Y') }} Your Company. All rights reserved.
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
