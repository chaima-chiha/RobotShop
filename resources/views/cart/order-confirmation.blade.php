@extends('layouts.app')

@section('title', 'Confirmation de Commande')

@section('content')
<div class="container py-5">

    <p>Votre commande a été passée avec succès!</p>

    <div id="order-details">
        <p>Chargement des détails de la commande...</p>
    </div>
    <div class="mt-4">
        <button id="print-btn" class="btn btn-primary" disabled>
            <i class="fas fa-print"></i> Imprimer la commande
        </button>


@include('cart.order-confirmation_js')


@endsection
