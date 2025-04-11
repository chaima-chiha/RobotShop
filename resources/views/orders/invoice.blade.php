@extends('layouts.app')

@section('title', 'Bon de Commande')

@section('content')
<div class="container py-5" id="invoice">
    <div id="invoice-container">
        <div class="text-center mb-4">
            <h3>🧾 Bon de Commande</h3>
        </div>

        <div id="invoice-content">
            <p>Chargement en cours...</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');
    const invoiceContent = document.getElementById('invoice-content');

    if (!orderId) {
        invoiceContent.innerHTML = '<div class="alert alert-warning">Aucune commande fournie.</div>';
        return;
    }

    axios.get(`/api/orders/${orderId}`, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    })
    .then(response => {
        if (response.data.success) {
            const order = response.data.data;

            let html = `
                <p><strong>Commande #${order.id}</strong></p>
                <p><strong>Client:</strong> ${order.nom}</p>
                <p><strong>Adresse:</strong> ${order.adresse}</p>
                <p><strong>Téléphone:</strong> ${order.telephone}</p>
                <p><strong>Méthode de livraison:</strong> ${order.livraison}</p>

                <h5 class="mt-4">Produits commandés :</h5>
                <table class="table mt-2">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            order.items.forEach(item => {
                const product = item.product;
                html += `
                    <tr>
                        <td><img src="${product.image ? '/storage/' + product.image : '/images/default.png'}" width="50"/></td>
                        <td>${product.name}</td>
                        <td>${item.quantity}</td>
                        <td>${item.price} TND</td>
                        <td>${(item.price * item.quantity).toFixed(2)} TND</td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
                <div class="text-end mt-3">
                    <h5>Total: ${order.total} TND</h5>
                </div>
            `;

            invoiceContent.innerHTML = html;
        } else {
            invoiceContent.innerHTML = '<div class="alert alert-danger">Commande introuvable.</div>';
        }
    })
    .catch(error => {
        console.error(error);
        invoiceContent.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement de la commande.</div>';
    });
});
</script>
@endsection
