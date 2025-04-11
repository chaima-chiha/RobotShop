@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')
<div class="container py-5">

    <div class="text-center mb-4">
        <h2 class="fw-bold">🧾 Mes Commandes</h2>
        <p class="text-muted">Visualisez l'historique de vos commandes passées</p>
    </div>

    <div id="orders-table" class="table-responsive rounded shadow-sm p-3 bg-white">
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2 text-muted">Chargement de vos commandes...</p>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="orderDetailsModalLabel">Détails de la Commande</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-order-details">
                <!-- Le contenu dynamique des détails de la commande sera injecté ici -->
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
