@extends('layouts.app')

@section('title', 'Bon de Commande')

@section('content')

<div class="text-end">
    <button class="btn btn-outline-primary" onclick="window.print()">
        <i class="fas fa-print"></i> Imprimer
    </button>
</div>
    <div class="container py-5" id="invoice">
        <!-- Le contenu complet du bon de commande -->
        <h2 class="mb-4">Bon de Commande #{{ $order->id }}</h2>
        <p><strong>Client :</strong> {{ $order->nom }}</p>
        <p><strong>Adresse :</strong> {{ $order->adresse }}</p>
        <p><strong>Téléphone :</strong> {{ $order->telephone }}</p>
        <!-- Liste des produits -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->price }} TND</td>
                        <td>{{ number_format($item->quantity * $item->price, 2) }} TND</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end mt-3">
            <h4>Total : {{ $order->total }} TND</h4>
        </div>
    </div>
@endsection
