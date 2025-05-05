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
                            <th>Modification</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            `;

            const tbody = ordersTableDiv.querySelector('tbody');

            orders.forEach(order => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><span class="badge bg-success">#${order.id}</span></td>
                    <td><strong>${order.total} TND</strong></td>
                    <td>
                        <button class="btn btn-outline-primary btn-sm view-order" data-id="${order.id}">
                            <i class="fas fa-eye me-1"></i> Voir
                        </button>
                        <span class="badge bg-${getStatusColor(order.status)} ms-2">${order.status}</span>
                    </td>
                    <td>
                        ${order.status === 'en_attente' || order.status === 'annulée'
                            ? `<button class="btn btn-light btn-sm edit-order" data-id="${order.id}">
                                <i class="fas fa-edit me-1"></i> Modifier
                               </button>`
                            : `<span class="badge bg-secondary">Non modifiable</span>`}
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

                            let total = parseFloat(order.total);
                            let fraisLivraison = 0;

                            if (order.livraison.toLowerCase() === 'domicile') {
                                fraisLivraison = 7;
                                total += fraisLivraison;
                            }

                            html += `
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="text-end mt-4">
                                            <p class="mb-1"><strong>Frais de livraison :</strong> ${fraisLivraison.toFixed(2)} TND</p>
                                            <h4 class="text-success">Total : ${total.toFixed(2)} TND</h4>
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

            // Modifier commande
            document.querySelectorAll('.edit-order').forEach(button => {
                button.addEventListener('click', async function () {
                    const orderId = this.dataset.id;
                    const modalContent = document.getElementById('edit-order-content');
                    modalContent.innerHTML = 'Chargement...';

                    try {
                        const res = await axios.get(`/api/orders/${orderId}`, {
                            headers: { 'Authorization': `Bearer ${token}` }
                        });
                        const order = res.data.data;

                        let html = `
                            <form id="edit-order-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nom</label>
                                        <input type="text" name="nom" class="form-control" value="${order.nom}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Adresse</label>
                                        <input type="text" name="adresse" class="form-control" value="${order.adresse}" required>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label>Téléphone</label>
                                        <input type="text" name="telephone" class="form-control" value="${order.telephone}" required>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label>Livraison</label>
                                        <select name="livraison" class="form-select">
                                            <option value="retrait" ${order.livraison === 'retrait' ? 'selected' : ''}>Retrait</option>
                                            <option value="domicile" ${order.livraison === 'domicile' ? 'selected' : ''}>À domicile</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label>Statut</label>
                                        <select name="status" class="form-select">
                                            <option value="en_attente" ${order.status === 'en_attente' ? 'selected' : ''}>En attente</option>
                                            <option value="annulée" ${order.status === 'annulée' ? 'selected' : ''}>Annulée</option>
                                        </select>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <div id="products-container">
                        `;

                        order.items.forEach((item, i) => {
                            const product = item.product;
                            const video = item.video;
                            const name = product ? product.name : (video ? video.title : 'N/A');
                            const type = product ? 'Produit' : (video ? 'Vidéo' : 'Inconnu');
                            const image = product ? (product.image ? '/storage/' + product.image : '/images/default.png') : (video ? (video.thumbnail ? '/storage/' + video.thumbnail : '/images/default.png') : '/images/default.png');

                            html += `
                                <div class="row mb-2 product-item" data-index="${i}">
                                    <input type="hidden" name="products[${i}][product_id]" value="${product ? product.id : ''}">
                                    <input type="hidden" name="products[${i}][video_id]" value="${video ? video.id : ''}">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control product-search" value="${name}" readonly>
                                        <div class="autocomplete-results list-group mt-1"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="products[${i}][quantity]" class="form-control" value="${item.quantity}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" name="products[${i}][price]" class="form-control" value="${item.price}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-danger remove-product-btn">Supprimer</button>
                                    </div>
                                </div>
                            `;
                        });

                        html += `</div>
                            </form>`;

                        modalContent.innerHTML = html;
                        new bootstrap.Modal(document.getElementById('editOrderModal')).show();

                        modalContent.addEventListener('click', function (e) {
                            if (e.target.classList.contains('remove-product-btn')) {
                                e.target.closest('.product-item').remove();
                            }
                        });

                        document.getElementById('edit-order-form').addEventListener('submit', async function (e) {
                            e.preventDefault();
                            const form = e.target;
                            const formData = new FormData(form);
                            const data = Object.fromEntries(formData.entries());

                            const products = [];
                            form.querySelectorAll('.product-item').forEach(el => {
                                const index = el.dataset.index;
                              products.push({
    product_id: el.querySelector(`[name="products[${index}][product_id]"]`).value === '' ? null : el.querySelector(`[name="products[${index}][product_id]"]`).value,
    video_id: el.querySelector(`[name="products[${index}][video_id]"]`).value === '' ? null : el.querySelector(`[name="products[${index}][video_id]"]`).value,
    quantity: el.querySelector(`[name="products[${index}][quantity]"]`).value,
    price: el.querySelector(`[name="products[${index}][price]"]`).value,
});

                            });

                            const payload = {
                                nom: data.nom,
                                adresse: data.adresse,
                                telephone: data.telephone,
                                livraison: data.livraison,
                                status: data.status,
                                items: products
                            };

                            try {
                                await axios.put(`/api/orders/${orderId}`, payload, {
                                    headers: { 'Authorization': `Bearer ${token}` }
                                });
                                showAlert('success', 'Commande mise à jour avec succès');
                                setTimeout(() => location.reload(), 6000);
                            } catch (err) {
                                console.error(err);
                                showAlert('danger', 'Erreur lors de la mise à jour.');
                            }
                        });

                    } catch (err) {
                        modalContent.innerHTML = '<p class="text-danger">Erreur lors du chargement.</p>';
                    }
                });
            });

            function getStatusColor(status) {
                switch (status.toLowerCase()) {
                    case 'en_attente':
                        return 'warning';
                    case 'confirmée':
                        return 'success';
                    case 'en cours':
                        return 'info';
                    case 'livrée':
                        return 'primary';
                    case 'annulée':
                        return 'danger';
                    default:
                        return 'dark';
                }
            }

            function showAlert(type, message) {
                const placeholder = document.getElementById('modalAlertPlaceholder');
                placeholder.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des commandes:', error);
            const ordersTableDiv = document.getElementById('orders-table');
            ordersTableDiv.innerHTML = '<p class="text-danger">Erreur lors du chargement des commandes.</p>';
        });
    });
    </script>
