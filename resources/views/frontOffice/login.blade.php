@extends('frontOffice.layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="max-w-md mx-auto mt-8 bg-white rounded-lg shadow-md p-8">
    <h1 class="text-2xl font-bold text-center mb-6">Connexion</h1>

    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-center">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-gray-700 mb-1">Email</label>
            <input type="email" name="email" required class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 mb-1">Mot de passe</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Connexion</button>
    </form>
</div>
@endsection
