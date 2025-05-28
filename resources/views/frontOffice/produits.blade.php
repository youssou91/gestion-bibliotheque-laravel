@extends('frontOffice.layouts.app')

@section('title', 'Produits')

@section('content')
<h1 class="text-2xl font-bold mb-6">Catégories & Produits</h1>

<form method="get" class="mb-6 flex flex-wrap gap-4 items-end">
    <div>
        <label for="category" class="block font-semibold mb-1">Catégorie</label>
        <select name="category" id="category" class="border rounded px-2 py-1">
            <option value="">Toutes</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="search" class="block font-semibold mb-1">Recherche</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" class="border rounded px-2 py-1" placeholder="Nom du produit...">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrer</button>
</form>

<ul class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @foreach($ouvrages as $ouvrage)
        <li class="border p-4 rounded shadow">
            <h2 class="text-lg font-bold mb-2">{{ $ouvrage->titre }}</h2>
            <p>Auteur : {{ $ouvrage->auteur }}</p>
            <p>Catégorie : {{ $ouvrage->categorie->name ?? 'Inconnue' }}</p>
            <p>Prix : {{ $ouvrage->prix }} $</p>
        </li>
    @endforeach
</ul>
@endsection
