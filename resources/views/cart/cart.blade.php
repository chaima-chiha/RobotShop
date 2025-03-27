@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

<div class="container-fluid py-5">
    <div class="container py-5">
        <div id="loading" style="text-align: center;">téléchargement des produits </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Produit</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Total</th>
                    <th scope="col">détaché</th>
                  </tr>
                </thead>
                <tbody id="cart-table-body">

                    @include('cart.cart_js')
                </tbody>
            </table>
        </div>

        <div class="row g-4 justify-content-end">
            <div class="col-8"></div>
            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="bg-light rounded">
                    <div class="d-flex justify-content-between mb-4">
                        <h5>Total:</h5>
                        <p id="cart-total">DT0.00</p>
                    </div>
                    <button id="vider-cart-btn" class="btn btn-primary btn-lg btn-block">vider le panier</button>
                    <button id="validate-order-btn" class="btn btn-primary btn-lg btn-block">passer la commande</button>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

