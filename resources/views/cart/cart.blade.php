@extends('layouts.app')

@section('title', 'Mon Panier')

@section('content')

<div class="container py-5">
    <div id="alert-container" class="mb-4"></div>

    <h1 class="text-center mb-5">🛒 Mon Panier</h1>

    <div class="table-responsive shadow rounded">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-success text-center">
                <tr>
                    <th scope="col">Produit</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Total</th>
                    <th scope="col">Détacher</th>
                </tr>
            </thead>
            <tbody id="cart-table-body" class="text-center">

            </tbody>
        </table>
        <div id="loading" class="text-center py-3 text-muted">Chargement des produits...</div>
    </div>

    <!-- Section totale + aide -->
    <div class="row justify-content-between mt-5 g-4">
        <!-- Colonne gauche avec le message -->
        <div class="col-md-6 col-lg-7">
            <div class="p-4 bg-light rounded shadow-sm h-100 d-flex flex-column justify-content-center">
                <h6 class="text-muted mb-2">
                    <i class="fas fa-info-circle me-2"></i>Besoin d’aide ?
                </h6>
                <p class="mb-0 text-muted">
                    Vérifiez bien votre panier avant de passer commande. Vous pouvez aussi continuer vos achats pour ne rien oublier !
                </p>
            </div>
        </div>

        <!-- Colonne droite avec total et actions -->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="fw-bold">Total :</h5>
                        <h5 id="cart-total" class="text-success">DT 0.00</h5>
                    </div>
                    <div class="d-grid gap-3">
                        <button id="vider-cart-btn" class="btn btn-outline-danger btn-lg">
                            <i class="fas fa-trash-alt me-2"></i>Vider le panier
                        </button>

                        <a href="/products" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Continuer les achats
                        </a>

                        <button id="validate-order-btn" class="btn btn-success btn-lg">
                            <i class="fas fa-check-circle me-2"></i>Passer la commande
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@include('cart.cart_js')
@include('layouts.alerts_js')
@endsection
