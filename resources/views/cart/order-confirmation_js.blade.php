
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const orderId = urlParams.get('order_id');
        const printBtn = document.getElementById('print-btn');

        if (orderId) {
        axios.get(`/api/orders/${orderId}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
                .then(response => {
                    if (response.data.success) {
                        const order = response.data.data;
                        const container = document.getElementById('order-details');

                        let html = `
                            <h2>Commande n°${order.id}</h2>
                            <p>Total: ${order.total} TND</p>
                            <h3>Produits:</h3>
                            <ul>
                        `;

                        order.items.forEach(item => {
                            html += `
                                <li>
                                    Produit: ${item.product.name} <br>
                                    Quantité: ${item.quantity} <br>
                                    Prix: ${item.price} TND
                                </li><br>
                            `;
                        });

                        html += '</ul>';
                        container.innerHTML = html;

                          // Activer le bouton d'impression
            printBtn.disabled = false;
            printBtn.addEventListener('click', () => {
                window.open(`/orders/${orderId}/print`, '_blank');
            });

                    } else {
                        document.getElementById('order-details').innerText = 'Erreur lors du chargement de la commande.';
                    }
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('order-details').innerText = 'Erreur lors de la récupération de la commande.';
                });
        } else {
            document.getElementById('order-details').innerText = 'Aucune commande trouvée.';
        }
    });
</script>
