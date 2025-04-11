<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('token');


        // Fetch orders
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
                </td>
            `;
                tbody.appendChild(row);
            });

            // Add event listeners to view order buttons
            document.querySelectorAll('.view-order').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-id');
                    axios.get(`/api/orders/${orderId}`, {
                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                    })
                    .then(response => {
                        const orderDetails = response.data.data;
                        const modalOrderDetailsDiv = document.getElementById('modal-order-details');
                        modalOrderDetailsDiv.innerHTML = `
                            <p><strong>Order ID:</strong> ${orderDetails.id}</p>
                            <p><strong>Total:</strong> ${orderDetails.total}</p>
                            <p><strong>Items:</strong></p>
                            <ul>${orderDetails.items.map(item => `<li>${item.quantity} x ${item.product.name} - ${item.price} each</li>`).join('')}</ul>
                        `;
                        // Open the modal
                        const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des détails de la commande:', error);
                        const modalOrderDetailsDiv = document.getElementById('modal-order-details');
                        modalOrderDetailsDiv.innerHTML = '<p>Failed to load order details.</p>';
                    });
                });
            });
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des commandes:', error);
            const ordersTableDiv = document.getElementById('orders-table');
            ordersTableDiv.innerHTML = '<p>Failed to load orders.</p>';
        });


    });
    </script>
