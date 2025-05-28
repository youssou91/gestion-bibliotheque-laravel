<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Dashboard Admin Template</title>
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
                        <i class="fas fa-user-plus fa-3x tm-site-icon text-center"></i>
                        <h2 class="tm-block-title mt-3">Créer un compte</h2>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="tm-login-form">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nom">Nom</label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                                    @error('nom') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                    @error('prenom') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="adresse">Adresse</label>
                                    <input type="text" class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" value="{{ old('adresse') }}" required>
                                    @error('adresse') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="telephone">Téléphone</label>
                                    <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                                    @error('telephone') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Adresse Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="password">Mot de passe</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    @error('password') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password-confirm">Confirmation</label>
                                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="photo">Photo de profil</label>
                                <input type="file" class="form-control-file @error('photo') is-invalid @enderror" id="photo" name="photo">
                                @error('photo') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">S'inscrire</button>
                            </div>

                            <div class="form-group text-center">
                                <p>Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="row tm-mt-big">
    <div class="col-12 font-weight-light text-center">
        <p class="d-inline-block tm-bg-black text-white py-2 px-4">
            &copy; 2025 SENCAM ELECTRONIQUE - Design by <a rel="nofollow" href="https://www.tooplate.com" class="text-white tm-footer-link">Tooplate</a>
        </p>
    </div>
</footer>
</body>
</html>
