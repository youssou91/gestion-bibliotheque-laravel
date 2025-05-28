@extends('frontOffice.layouts.app')
@section('content')
    <body id="reportsPage">
        <div id="home">
            <div class="row">
                <div class="col-12">
                    <div class="bg-white tm-block">
                        <form method="get" action="{{ route('produits') }}"
                            class="flex flex-col md:flex-row gap-2 mb-8 justify-center">
                            <input type="text" name="search" placeholder="Rechercher un produit..."
                                value="{{ request('search') }}" class="px-4 py-2 border rounded w-full md:w-1/3">

                            <select name="category" class="px-4 py-2 border rounded md:w-1/4">
                                <option value="" disabled selected>Toutes catégories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-4 py-2 bg-blue-600  rounded">Rechercher</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection
