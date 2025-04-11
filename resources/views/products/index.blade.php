@extends('layouts.app')

@section('content')

<div class="container py-5">

    <!-- Titre & Filtres -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6 text-md-start text-center mb-3 mb-md-0">
            <h1 class="fw-bold">Nos Produits</h1>
        </div>
        <div class="col-md-6 text-md-end text-center">
            <label for="filter" class="form-label me-2">Filtrer par :</label>
            <select id="filter" class="form-select d-inline-block w-auto">
                <option value="all">Tous les produits</option>
                <option value="promo">Produits en promotion</option>
                <option value="new">Nouveaux produits</option>
                <option value="price_asc">Prix croissant</option>
                <option value="price_desc">Prix décroissant</option>
                <option value="name_asc">Nom (A-Z)</option>
                <option value="name_desc">Nom (Z-A)</option>
            </select>
        </div>
    </div>

    <!-- Chargement -->
    <div id="loading" class="text-center my-4" style="display: none;">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        <p class="mt-2">Téléchargement des produits...</p>
    </div>

    <!-- Liste des produits -->
    <div class="row g-4" id="products">
        @include('products.index_js')
    </div>

</div>

@endsection
