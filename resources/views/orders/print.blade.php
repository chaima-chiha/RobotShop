<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Commande #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-weight: bold; font-size: 1.2em; margin-top: 20px; }
        .footer { margin-top: 50px; font-size: 12px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Commande #{{ $order->id }}</h1>
        <p>Date: {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info">
        <h2>Client</h2>
        <p><strong>Nom:</strong> {{ $order->user->name }}</p>
        <p><strong>Email:</strong> {{ $order->user->email }}</p>
    </div>

    <h2>Articles</h2>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 3, ',', ' ') }} TND</td>
                <td>{{ number_format($item->price * $item->quantity, 3, ',', ' ') }} TND</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total général: {{ number_format($order->total, 3, ',', ' ') }} TND</p>
    </div>

    <div class="footer">
        <p>Merci pour votre commande !</p>
        <p>{{ config('app.name') }}</p>
    </div>
</body>
</html>
