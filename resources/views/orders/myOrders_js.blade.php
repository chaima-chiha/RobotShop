<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('token');

        // Récupérer les commandes
        axios.get('/api/orders', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            const orders = response.data.data;
            const ordersTableDiv = document.getElementById('orders-table');
            ordersTableDiv.innerHTML = `
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID Commande</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            `;

            const tbody = ordersTableDiv.querySelector('tbody');

            orders.forEach(order => {
                const row = document.createElement('tr');
                row.innerHTML = `
    <td><span class="badge bg-secondary">#${order.id}</span></td>
    <td><strong>${order.total} TND</strong></td>
    <td>
        <button class="btn btn-outline-primary btn-sm view-order" data-id="${order.id}">
            <i class="fas fa-eye me-1"></i> Voir
        </button>
        <span class="badge bg-${getStatusColor(order.status)} ms-2">${order.status}</span>
    </td>
`;

                tbody.appendChild(row);
            });

            // Écouteurs pour les boutons "Voir"
            document.querySelectorAll('.view-order').forEach(button => {
    button.addEventListener('click', function () {
        const orderId = this.getAttribute('data-id');
        const modalOrderDetailsDiv = document.getElementById('modal-order-details');
        modalOrderDetailsDiv.innerHTML = '<p class="text-muted text-center">Chargement du bon de commande...</p>';

        axios.get(`/api/orders/${orderId}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(response => {
            if (response.data.success) {
                const order = response.data.data;

                let html = `
    <div class="card shadow rounded-4 border-0">
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
                            <td><img src="${product.image ? '/storage/' + product.image : '/images/default.png'}" width="50" class="rounded" /></td>
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
            </div>

            <div class="text-end mt-4">
                <h4 class="text-success">Total : ${order.total} TND</h4>
            </div>
        </div> <!-- fin de .card-body -->
    </div> <!-- fin de .card -->
`;


                modalOrderDetailsDiv.innerHTML = html;

            } else {
                modalOrderDetailsDiv.innerHTML = `
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-times-circle"></i> Commande introuvable.
                    </div>`;
            }

            const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Erreur lors du chargement du bon de commande :', error);
            modalOrderDetailsDiv.innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle"></i> Erreur lors du chargement de la commande.
                </div>`;
        });
    });
});


        })
        .catch(error => {
            console.error('Erreur lors de la récupération des commandes:', error);
            const ordersTableDiv = document.getElementById('orders-table');
            ordersTableDiv.innerHTML = '<p class="text-danger">Erreur lors du chargement des commandes.</p>';
        });


        function getStatusColor(status) {
    switch (status.toLowerCase()) {
        case 'en attente':
            return 'warning';
        case 'confirmée':
            return 'success';
        case 'en cours':
            return 'info';
        case 'livrée':
            return 'primary';
        default:
            return 'dark';
    }
}

    });
    </script>
