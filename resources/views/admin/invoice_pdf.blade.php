<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon de Commande</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Bon de Commande n°{{ $order->id }}</h2>
    <p><strong>Client:</strong> {{ $order->nom }}</p>
    <p><strong>Adresse:</strong> {{ $order->adresse }}</p>
    <p><strong>Téléphone:</strong> {{ $order->telephone }}</p>
    <p><strong>Méthode de livraison:</strong> {{ $order->livraison }}</p>

    <h4>Produits :</h4>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Prix</th>
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
                    <td>{{ $item->price }} TND</td>
                    <td>{{ number_format($item->price * $item->quantity, 2) }} TND</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align: right; margin-top: 20px;"><strong>Total : {{ $order->total }} TND</strong></p>
</body>
</html>
