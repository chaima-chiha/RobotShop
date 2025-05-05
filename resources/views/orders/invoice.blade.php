@extends('layouts.app')

@section('title', 'Bon de Commande')

@section('content')
<div class="container py-5" id="invoice">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-success-subtle text-dark py-4 rounded-top-4">
                    <div class="row align-items-center">
                        <!-- Logo -->
                        <div class="col-md-4 text-start">
                            <img src="{{ asset('img/mylogo.png') }}" alt="logo" class="img-fluid" style="max-width: 150px;">
                        </div>

                        <!-- Titre principal -->
                        <div class="col-md-4 text-center">
                            <h3 class="mb-0">Bon de Commande</h3>
                        </div>

                        <!-- Coordonnées -->
                        <div class="col-md-4 text-end small">
                            <div><i class="fas fa-phone-alt me-1"></i> +216 99 847 516</div>
                            <div><i class="fas fa-envelope me-1"></i> RobotShopAcademy@gmail.com</div>
                            <div><i class="fas fa-map-marker-alt me-1"></i> 19 Rue El Jahedh, Nabeul</div>
                        </div>
                    </div>
                </div>

                <div class="card-body" id="invoice-content">
                    <p class="text-center text-muted">Chargement en cours...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');
    const invoiceContent = document.getElementById('invoice-content');

    if (!orderId) {
        invoiceContent.innerHTML = `
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle"></i> Aucune commande fournie.
            </div>`;
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
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Commande N°${order.id}</strong></p>
                        <p><strong>Client:</strong> ${order.nom}</p>
                        <p><strong>Adresse:</strong> ${order.adresse}</p>
                        <p><strong>Téléphone:</strong> ${order.telephone}</p>
                        <p><strong>Méthode de livraison:</strong> ${order.livraison}</p>
                    </div>
                </div>

                <h5 class="text-secondary mt-4">Produits commandés :</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            order.items.forEach(item => {
                const product = item.product;
                const video = item.video;
                const name = product ? product.name : (video ? video.title : 'N/A');
                const type = product ? 'Produit' : (video ? 'Vidéo' : 'Inconnu');
                const image = product ? (product.image ? '/storage/' + product.image : '/images/default.png') : (video ? (video.thumbnail ? '/storage/' + video.thumbnail : '/images/default.png') : '/images/default.png');

                html += `
                    <tr>
                        <td><img src="${image}" width="50" class="rounded" /></td>
                        <td>${name}</td>
                        <td>${type}</td>
                        <td>${item.quantity}</td>
                        <td>${item.price} TND</td>
                        <td>${(item.price * item.quantity).toFixed(2)} TND</td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <p class="mb-1"><strong>Frais de livraison :</strong> ${order.livraison === 'domicile' ? '7 TND' : '0 TND'}</p>
                    <h4 class="text-success">Total : ${order.total} TND</h4>
                </div>
            `;

            invoiceContent.innerHTML = html;
        } else {
            invoiceContent.innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="fas fa-times-circle"></i> Commande introuvable.
                </div>`;
        }
    })
    .catch(error => {
        console.error(error);
        invoiceContent.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-circle"></i> Erreur lors du chargement de la commande.
            </div>`;
    });
});
</script>
@endsection
