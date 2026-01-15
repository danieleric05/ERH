<!doctype html>
<html lang="en">

<head>
    <title>:: ERH :: Application ressources humaines</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com">

    <link rel="icon" href="{{ asset('rhassets/images/logoo.png') }}" type="image/x-icon">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/font-awesome/css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('rhassets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/sweetalert/sweetalert.css') }}" />

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{ asset('rhassets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/css/color_skins.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/css/erh.css') }}">

    <style>
        td.details-control {
            background: url("{{ asset('rhassets/images/details_open.png') }}") no-repeat center center;
        }

        tr.shown td.details-control {
            background: url("{{ asset('rhassets/images/details_close.png') }}") no-repeat center center;
        }
    </style>
    @stack('styles')
</head>

<body class="theme-orange">

    <!-- Page Loader
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-5"><img src="{{ asset('rhassets/images/logoo.png') }}" width="270" height="200" alt="Logo-Plastica"></div>
        <p>Please wait...</p>
    </div>
</div>
Overlay For Sidebars -->

    <div id="wrapper">

        <nav class="navbar navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-btn">
                    <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
                </div>

                <div class="navbar-brand">
                    <a href="{{ url('bienvenue') }}"><img src="{{ asset('rhassets/images/logop.png') }}" height="30" alt="Logo" class="img-responsive logo"></a>
                </div>

                <div class="navbar-right">
                    <div id="navbar-menu">
                        <ul class="nav navbar-nav">
                            <!--<li><a href="app-events.html" class="icon-menu d-none d-sm-block d-md-none d-lg-block"><i class="icon-calendar"></i></a></li>
                        <li><a href="app-chat.html" class="icon-menu d-none d-sm-block"><i class="icon-bubbles"></i></a></li>
                        <li><a href="app-inbox.html" class="icon-menu d-none d-sm-block"><i class="icon-envelope"></i><span class="notification-dot"></span></a></li>
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                <i class="icon-bell"></i>
                                <span class="notification-dot"></span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="icon-equalizer"></i></a>
                        </li>-->
                            <li><a href="{{ route('logoutUser', Auth::user()->id) }}" class="icon-menu"><i class="icon-login"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        @include('insert.link')

        <div id="main-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </div>
    <div class="load">
        <img src="{{ asset('rhassets/images/load3.gif') }}" class="img-fluid loading">
    </div>
    <!-- Javascript -->
    <script src="{{ asset('rhassets/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('rhassets/bundles/vendorscripts.bundle.js') }}"></script>

    <script src="{{ asset('rhassets/bundles/datatablescripts.bundle.js') }}"></script>
    <script src="{{ asset('rhassets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script><script src="{{ asset('rhassets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('rhassets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('rhassets/vendor/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('rhassets/vendor/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('rhassets/vendor/jquery-datatable/buttons/buttons.print.min.js') }}"></script>

    <script src="{{ asset('rhassets/vendor/sweetalert/sweetalert.min.js') }}"></script> <!-- SweetAlert Plugin Js -->

    <script src="{{ asset('rhassets/bundles/mainscripts.bundle.js') }}"></script>
    <script src="{{ asset('rhassets/js/pages/tables/jquery-datatable.js') }}"></script>

    <script src="{{ asset('erhjs/scriptjs.js') }}"></script>
    <script src="{{ asset('insert/departement.js') }}"></script>
    <script src="{{ asset('insert/equipe.js') }}"></script>
    <script src="{{ asset('insert/fonction.js') }}"></script>
    <script src="{{ asset('insert/pays.js') }}"></script>
    <script src="{{ asset('insert/niveauEtude.js') }}"></script>
    <script src="{{ asset('insert/categorie.js') }}"></script>
    <script src="{{ asset('insert/recrutement.js') }}"></script>
    <script src="{{ asset('insert/accident_travail.js') }}"></script>
    <script src="{{ asset('insert/unites.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    @stack('scripts')
</body>

</html>