<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Bibliothèque - Connexion</title>
    <link href="{{ url('./assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{ url('./assets/vendors/themify-icons/css/themify-icons.css')}}" rel="stylesheet" />
    <link href="{{ url('assets/css/main.min.css')}}" rel="stylesheet" />
    <style>
        .login-content {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .brand {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand .logo {
            font-size: 50px;
            color: #fff;
            margin-bottom: 15px;
        }
        .brand .link {
            font-size: 32px;
            color: #fff;
            text-decoration: none;
            font-weight: 500;
        }
        .login-box {
            max-width: 400px;
            width: 100%;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }
        .login-box-header {
            margin-bottom: 30px;
        }
        .login-box-header h4 {
            color: #333;
            font-size: 24px;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .input-group-icon {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        .form-control {
            padding-left: 45px;
            height: 45px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.25);
        }
        .btn-login {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            height: 45px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .forgot-link {
            color: #764ba2;
            text-decoration: none;
            transition: all 0.3s;
        }
        .forgot-link:hover {
            color: #667eea;
            text-decoration: none;
        }
        .ui-checkbox {
            color: #666;
        }
    </style>
</head>

<body>
    <div class="login-content">
        <div class="login-wrapper">
            <div class="brand">
                <div class="logo">
                    <i class="fa fa-book"></i>
                </div>
                <a class="link" href="/">Bibliothèque</a>
            </div>
        <div class="login-box">
            <div class="login-box-header">
                <div class="text-center"><h4>Connexion</h4></div>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <div class="input-group-icon right">
                        <div class="input-icon"><i class="fa fa-envelope"></i></div>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group-icon right">
                        <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Mot de passe" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group d-flex justify-content-between">
                    <label class="ui-checkbox ui-checkbox-info">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="input-span"></span>Se souvenir de moi</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            <i class="fa fa-lock mr-1"></i> Mot de passe oublié?
                        </a>
                    @endif
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block btn-login" type="submit">
                        <i class="fa fa-sign-in mr-2"></i> Se connecter
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- CORE PLUGINS -->
    <script src="{{ url('./assets/vendors/jquery/dist/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/popper.js/dist/umd/popper.min.js')}}" type="text/javascript"></script>
    <script src="{{ url('./assets/vendors/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <!-- CORE SCRIPTS-->
    <script src="{{ url('assets/js/app.min.js')}}" type="text/javascript"></script>
</body>
</html>
