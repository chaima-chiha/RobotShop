@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Titre & Description -->
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-md-8">
            <h1 id="categoryName" class="fw-bold">catégorie</h1>
            <p id="categoryDescription" class="text-muted"></p>
        </div>

        <!-- Filtre -->
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <label for="filter" class="form-label mb-1">Filtrer par :</label>
            <select id="filter" class="form-select">
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

    <!-- Loader -->
    <div id="loading" class="text-center mb-4" style="display: none;">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>

    <!-- Produits -->
    <div class="row g-4" id="products"></div>

</div>

@include('products.categories_show_js')
@endsection
