<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>Login Dashboard Admin</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
        rel="stylesheet">
    <link href="{{ asset("plugins/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet">
    <link href="{{ asset("plugins/font-awesome/css/all.min.css") }}" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="{{ asset("css/connect.min.css") }}" rel="stylesheet">
    <link href="{{ asset("css/dark_theme.css") }}" rel="stylesheet">
    <link href="{{ asset("css/custom.css") }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body class="auth-page sign-in">

    <div class='loader'>
        <div class='spinner-grow text-primary' role='status'>
            <span class='sr-only'>Memuat Halaman...</span>
        </div>
    </div>
    <div class="connect-container align-content-stretch d-flex flex-wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-5">
                    <div class="auth-form">
                        <div class="row">
                            <div class="col">
                                @if (session("error"))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session("error") }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="logo-box"><a href="#" class="logo-text">Montir App</a></div>
                                <form action="{{ route("authenticate") }}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="email"
                                            aria-describedby="emailHelp" placeholder="masukkan email" name="email">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" id="password"
                                            placeholder="masukkan password" name="password">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block btn-submit">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block d-xl-block">
                    <div class="auth-image"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascripts -->
    <script src="{{ asset("plugins/jquery/jquery-3.4.1.min.js") }}"></script>
    <script src="{{ asset("plugins/bootstrap/popper.min.js") }}"></script>
    <script src="{{ asset("plugins/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset("plugins/jquery-slimscroll/jquery.slimscroll.min.js") }}"></script>
    <script src="{{ asset("js/connect.min.js") }}"></script>
</body>

</html>
