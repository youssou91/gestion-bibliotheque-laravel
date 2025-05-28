@extends('frontOffice.layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white/90 rounded-xl shadow-lg p-8">
    <h1 class="text-2xl font-bold text-center mb-6">Créer un compte</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
            <label for="nom" class="block text-gray-700 mb-1">Nom</label>
            <input type="text" name="nom" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="prenom" class="block text-gray-700 mb-1">Prénom</label>
            <input type="text" name="prenom" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 mb-1">Email</label>
            <input type="email" name="email" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 mb-1">Mot de passe</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">S’inscrire</button>
    </form>
</div>
@endsection
