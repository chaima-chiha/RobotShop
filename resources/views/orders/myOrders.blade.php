@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="app">


    <h2>Mes Commandes</h2>
    <div id="orders-table">
        <p>Loading orders...</p>
    </div>


    <!-- Modal Structure -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Détails de la Commande</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-order-details">
                    <p>Sélectionnez une commande pour voir les détails.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>


</div>


@include('orders.myOrders_js')
@endsection
