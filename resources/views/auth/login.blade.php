<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexion - SENCAM ELECTRONIQUE</title>

    <!-- Fonts and CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tooplate.css') }}">
</head>

<body class="bg03">
    <div class="container">
        <div class="row tm-mt-big">
            <div class="col-12 mx-auto tm-login-col">
                <div class="bg-white tm-block">
                    <div class="row">
                        <div class="col-12 text-center">
                            <i class="fas fa-3x fa-tachometer-alt tm-site-icon text-center"></i>
                            <h2 class="tm-block-title mt-3">Connexion</h2>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <form method="POST" action="{{ route('login') }}" class="tm-login-form">
                                @csrf
                                <div class="input-group">
                                    <label for="email" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                                           class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7 @error('email') is-invalid @enderror"
                                           required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="input-group mt-3">
                                    <label for="password" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Mot de passe</label>
                                    <input id="password" name="password" type="password"
                                           class="form-control validate @error('password') is-invalid @enderror"
                                           required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="input-group mt-3 justify-content-between align-items-center">
                                    <label class="ml-3">
                                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Se souvenir de moi
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-primary">Mot de passe oublié ?</a>
                                    @endif
                                </div>
                                <div class="input-group mt-4">
                                    <button type="submit" class="btn btn-primary d-inline-block mx-auto">
                                        <i class="fa fa-sign-in-alt mr-2"></i> Se connecter
                                    </button>
                                </div>
                                <div class="input-group mt-3 text-center">
                                    <p class="mb-0">Pas encore de compte ? <a href="{{ route('register') }}" class="text-primary">S'inscrire</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <footer class="row tm-mt-big">
            <div class="col-12 font-weight-light text-center">
                <p class="d-inline-block tm-bg-black text-white py-2 px-4">
                    &copy; {{ date('Y') }} SENCAM ELECTRONIQUE - Thème by
                    <a rel="nofollow" href="https://www.tooplate.com" class="text-white tm-footer-link">Tooplate</a>
                </p>
            </div>
        </footer> --}}
    </div>
</body>

</html>
