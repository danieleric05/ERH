<!doctype html>
<html lang="en">

<head>
    <title>:: ERH :: Login</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="PLASTICA ERH">
    <meta name="author" content="PLASTICA ERH">

    <link rel="icon" href="{{ asset('rhassets/images/logoo.png') }}" type="image/x-icon">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/vendor/font-awesome/css/font-awesome.min.css') }}">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{ asset('rhassets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/css/color_skins.css') }}">
    <link rel="stylesheet" href="{{ asset('rhassets/css/fixes.css') }}">

    <style>
        .auth-main:after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: url(rhassets/images/auth_bg.jpg) no-repeat top left fixed;
        }
    </style>

</head>

<body class="theme-orange">
    <!-- WRAPPER -->
    <div id="wrapper">
        <div class="vertical-align-wrap">
            <div class="vertical-align-middle auth-main">
                <div class="auth-box">
                    <div class="top">
                        <img src="{{ asset('rhassets/images/logoERH.png') }}" alt="Plastica">
                    </div>
                    <div class="card">
                        <div class="header text-center">
                            <p class="lead">Se connecter a son compte</p>
                        </div>

                        @include('success')
                        @include('errors')

                        <div class="body">
                            <form action="{{ url('post_login') }}" method="POST" role="form" class="form-auth-small">
                                @csrf
                                <div class="form-group">
                                    <label for="signin-email" class="control-label sr-only">Email</label>
                                    <input type="text" name="pseudo" required class="form-control" placeholder="Pseudo">
                                </div>
                                <div class="form-group">
                                    <label for="signin-password" class="control-label sr-only">Mot de Passe</label>
                                    <input type="password" name="password" required class="form-control" placeholder="Mot de passe">
                                </div>
                                <div class="form-group clearfix">
                                    <label class="fancy-checkbox element-left">
                                        <input type="checkbox">
                                        <span>Se Rappeler de moi</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
                                <div class="bottom">
                                    <span class="helper-text m-b-10"><i class="fa fa-lock"></i>
                                        <a href="#" onclick="addForm_mdp()" data-toggle="modal" data-target="#largeModal">
                                            Mot de passe oublié ?
                                        </a>
                                    </span>
                                    <span>
                                        Vous partez en mission ? Complétez le formulaire et le DRH vous contactera
                                        <a onclick="addForm_mission()" style="float: right; color: #fff;" data-toggle="modal" data-target="#largeModal" class="btn btn-danger m-t-15">
                                            Cliquez ici
                                        </a>
                                    </span>
                                </div>
                            </form>
                            {!! html()->form()->close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('add_mission')
    @include('add_mdp')
    <!-- END WRAPPER -->
    <script src="{{ asset('rhassets/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('rhassets/bundles/vendorscripts.bundle.js') }}"></script>
    <script src="{{ asset('insert/login.js') }}"></script>

</body>

</html>