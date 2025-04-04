@extends('layouts.app')

@section('content')

<div>
    <h1 id="categoryName">Produits de la catégorie</h1>
    <p id="categoryDescription"></p>

    <!-- Conteneur pour les produits -->
    <div id="loading" class="text-center" style="display: none;">
        Chargement en cours...
    </div>
    <div id="products" class="row"></div>
    @include('products.categories_show_js')
</div>

@endsection
