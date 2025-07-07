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
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/multi-select/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/nouislider/nouislider.min.css') }}" />

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{ asset('rhassets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/css/color_skins.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/css/color_perso.css') }}">

    <style>
        td.details-control {
            background: url('{{ asset('rhassets/images/details_open.png') }}') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('{{ asset('rhassets/images/details_close.png') }}') no-repeat center center;
        }
    </style>

</head>
<body class="theme-blue">

<!-- Page Loader
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-5"><img src="{{ asset('rhassets/images/logoo.png') }}" width="350" height="180" alt="Logo-Plastica"></div>
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
                <a href="{{ url('bienvenue') }}"><img src="{{ asset('rhassets/images/logop.png') }}" height="40" alt="Logo" class="img-responsive logo"></a>
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

<!-- Javascript -->
<script src="{{ asset('rhassets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('rhassets/bundles/vendorscripts.bundle.js') }}"></script>

<script src="{{ asset('rhassets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}"></script> <!-- Bootstrap Colorpicker Js -->
<script src="{{ asset('rhassets/vendor/jquery-inputmask/jquery.inputmask.bundle.js') }}"></script> <!-- Input Mask Plugin Js -->
<script src="{{ asset('rhassets/vendor/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('rhassets/vendor/multi-select/js/jquery.multi-select.js') }}"></script> <!-- Multi Select Plugin Js -->
<script src="{{ asset('rhassets/vendor/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
<script src="{{ asset('rhassets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('rhassets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script> <!-- Bootstrap Tags Input Plugin Js -->
<script src="{{ asset('rhassets/vendor/nouislider/nouislider.js') }}"></script> <!-- noUISlider Plugin Js -->
<script src="{{ asset('rhassets/vendor/select2/select2.min.js') }}"></script> <!-- Select2 Js -->

<script src="{{ asset('rhassets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('rhassets/js/pages/forms/advanced-form-elements.js') }}"></script>
<script src="{{ asset('erhjs/scriptjs.js') }}"></script>



</body>
</html>
