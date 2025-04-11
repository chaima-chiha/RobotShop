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
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">voir commande</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body" id="modal-order-details">
          <!-- Le contenu du bon de commande sera injecté ici -->
        </div>
      </div>
    </div>
  </div>



</div>

@include('orders.myOrders_js')
@endsection
