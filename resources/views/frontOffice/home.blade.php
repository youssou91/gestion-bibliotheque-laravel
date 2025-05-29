@extends('frontOffice.layouts.app')
@section('content')
    <body id="reportsPage">
        <div id="home">
            <div class="row">
                <div class="col-12">
                    <div class="bg-white tm-block">
                        <form method="get" action="{{ route('ouvrages') }}"
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
                    {{--  --}}
                    <div class="container mt-5">
                        <div class="row">
                            @foreach ($ouvrages as $livre)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 shadow-sm border-0 rounded">
                                        <img src="{{ asset('assets/img/' . $livre->photo) }}" class="card-img-top"
                                            alt="{{ $livre->titre }}" style="height: 350px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $livre->titre }}</h5>
                                            <p class="card-text">
                                                <small>Par {{ $livre->auteur }}</small><br>
                                                <strong>{{ $livre->quantite > 0 ? 'Disponible' : 'Indisponible' }}</strong>
                                            </p>
                                            <form action="{{ url('emprunts.store') }}" method="POST"
                                                class="d-flex align-items-center justify-content-between gap-3 mt-3 flex-wrap">
                                                @csrf
                                                <input type="hidden" name="livre_id" value="{{ $livre->id }}">
                                                <button type="submit" class="btn btn-sm btn-success border-0 rounded"
                                                    title="Emprunter ce livre"
                                                    {{ $livre->quantite <= 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-cart-plus fs-5"></i>
                                                </button>
                                                <a href="{{ route('livres.favoris', $livre->id) }}"
                                                    class="btn btn-sm btn-outline-danger border-0 rounded"
                                                    title="Ajouter aux favoris">
                                                    <i class="fas fa-heart fs-5"></i>
                                                </a>
                                                <a href="{{ route('livres.show', $livre->id) }}"
                                                    class="btn btn-sm btn-outline-primary border-0 rounded"
                                                    title="Voir les détails">
                                                    <i class="fas fa-eye fs-5"></i>
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $ouvrages->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                    {{--  --}}
                </div>
            </div>
        </div>
    </body>
@endsection
