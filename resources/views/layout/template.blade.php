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
        <title>@yield('title')</title>

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
        <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/DataTables/datatables.min.css') }}" rel="stylesheet">


        <!-- Theme Styles -->
        <link href="{{ asset('css/connect.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/dark_theme.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class='loader'>
            <div class='spinner-grow text-primary' role='status'>
                <span class='sr-only'>Memuat Halaman...</span>
            </div>
        </div>
        <div class="connect-container align-content-stretch d-flex flex-wrap">
            <div class="page-sidebar">
                <div class="logo-box"><a href="#" class="logo-text">MontirApp</a><a href="#" id="sidebar-close"><i class="material-icons">close</i></a> <a href="#" id="sidebar-state"><i class="material-icons">adjust</i><i class="material-icons compact-sidebar-icon">panorama_fish_eye</i></a></div>
                <div class="page-sidebar-inner slimscroll">
                    <ul class="accordion-menu">
                        <li class="{{ Route::currentRouteName() === 'dashboard' ? 'active-page' : '' }}">
                            <a href="{{ route('dashboard') }}"><i class="material-icons-outlined">dashboard</i>Dashboard</a>
                        </li>
                        <li class="{{ Route::currentRouteName() === 'pelanggan.index' ? 'active-page' : '' }}">
                            <a href="{{ route('pelanggan.index') }}"><i class="material-icons-outlined">account_circle</i>Pelanggan</a>
                        </li>
                        <li class="{{ Route::currentRouteName() === 'bengkel.index' ? 'active-page' : '' }}">
                            <a href="{{ route('bengkel.index') }}"><i class="material-icons-outlined">account_circle</i>Bengkel</a>
                        </li>
                        <li>
                            <a href="file-manager.html"><i class="material-icons">cloud_queue</i>Transaksi</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="page-container">
                <div class="page-header">
                    <nav class="navbar navbar-expand">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <ul class="navbar-nav">
                            <li class="nav-item small-screens-sidebar-link">
                                <a href="#" class="nav-link"><i class="material-icons-outlined">menu</i></a>
                            </li>
                            <li class="nav-item nav-profile dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ asset('images/avatars/profile-image-1.png') }}" alt="profile image">
                                    <span>{{ Auth::user()->nama }}</span><i class="material-icons dropdown-icon">keyboard_arrow_down</i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">Akun Saya</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                        @csrf
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Log out
                                        </a>
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" id="dark-theme-toggle"><i class="material-icons-outlined">brightness_2</i><i class="material-icons">brightness_2</i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="page-content">
                    @yield('breadcrumb')
                    <div class="main-wrapper">
                        @yield('main')
                    </div>
                </div>
                <div class="page-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="footer-text">2025 © Aprilmen Sihaloho</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Javascripts -->
        <script src="{{ asset('plugins/jquery/jquery-3.4.1.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/popper.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('plugins/apexcharts/dist/apexcharts.min.js') }}"></script>
        <script src="{{ asset('plugins/blockui/jquery.blockUI.js') }}"></script>
        <script src="{{ asset('plugins/flot/jquery.flot.min.js') }}"></script>
        <script src="{{ asset('plugins/flot/jquery.flot.time.min.js') }}"></script>
        <script src="{{ asset('plugins/flot/jquery.flot.symbol.min.js') }}"></script>
        <script src="{{ asset('plugins/flot/jquery.flot.resize.min.js') }}"></script>
        <script src="{{ asset('plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
        <script src="{{ asset('js/connect.min.js') }}"></script>
        <script src="{{ asset('js/pages/dashboard.js') }}"></script>

        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('js/pages/datatables.js') }}"></script>
    </body>
</html>
