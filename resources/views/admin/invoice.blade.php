<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon de Commande n°{{ $order->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS CDN (pour le style à l’impression) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        body {
            background: #fff;
        }
        .card {
            box-shadow: none;
            border: none;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="text-end no-print mb-3">
        <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimer
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-success-subtle text-dark py-4 rounded-top-4">
            <div class="row align-items-center">
                <div class="col-md-4 text-start">
                    <img src="/img/mylogo.png" alt="logo" class="img-fluid" style="max-width: 150px;">
                </div>
                <div class="col-md-4 text-center">
                    <h3 class="mb-0">Bon de Commande</h3>
                </div>
                <div class="col-md-4 text-end small">
                    <div><i class="fas fa-phone-alt me-1"></i> +216 99 847 516</div>
                    <div><i class="fas fa-envelope me-1"></i> RobotShopAcademy@gmail.com</div>
                    <div><i class="fas fa-map-marker-alt me-1"></i> 19 Rue El Jahedh, Nabeul</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Commande N°:</strong> {{ $order->id }}</p>
                    <p><strong>Client :</strong> {{ $order->nom }}</p>
                    <p><strong>Adresse :</strong> {{ $order->adresse }}</p>
                    <p><strong>Téléphone :</strong> {{ $order->telephone }}</p>
                    <p><strong>Livraison :</strong> {{ ucfirst($order->livraison) }}</p>
                </div>
            </div>

            <h5 class="text-secondary mt-4">Produits commandés :</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                @if ($item->product)
                                    <td>{{ $item->product->name }}</td>
                                    <td>Produit</td>
                                @elseif ($item->video)
                                    <td>{{ $item->video->title }}</td>
                                    <td>Vidéo</td>
                                @else
                                    <td>Inconnu</td>
                                    <td>Inconnu</td>
                                @endif
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }} TND</td>
                                <td>{{ number_format($item->quantity * $item->price, 2) }} TND</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @php
                $fraisLivraison = strtolower($order->livraison) === 'domicile' ? 7 : 0;
                $totalFinal = $order->total + $fraisLivraison;
            @endphp

            <div class="text-end mt-4">
                <p><strong>Frais de livraison :</strong> {{ number_format($fraisLivraison, 2) }} TND</p>
                <h4 class="text-success">Total : {{ number_format($totalFinal, 2) }} TND</h4>
            </div>
        </div>
    </div>
</div>

</body>
</html>
