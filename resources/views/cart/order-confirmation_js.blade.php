<script>

document.addEventListener('DOMContentLoaded', function () {
    const orderDetails = document.getElementById('order-summary-content');
    const totalElement = document.getElementById('order-total');
    const totalLivraisonBlock = document.getElementById('order-total-livraison');
    const totalLivraisonVal = document.getElementById('total-livraison-val');

    const savedCart = JSON.parse(localStorage.getItem('checkoutCart'));

    if (!savedCart || !savedCart.items.length) {
        orderDetails.innerHTML = '<div class="alert alert-warning text-center">Aucun article dans le panier.</div>';
        return;
    }

    let html = '<ul class="list-group">';

    savedCart.items.forEach(item => {
        const imageUrl = item.image ? `/storage/${item.image}` : '/default.png';

        html += `
            <li class="list-group-item d-flex align-items-center">
                <img src="${item.image}" alt="${item.name}" style="width: 60px; height: 60px; object-fit: cover;" class="rounded me-3">
                <div class="flex-grow-1">
                    <strong>${item.name}</strong><br>
                    <small>Quantité: ${item.quantity}</small><br>
                    <small>Prix unitaire: ${item.price.toFixed(2)} TND</small>
                </div>
                <span class="badge bg-primary rounded-pill ms-auto">
                    ${(item.price * item.quantity).toFixed(2)} TND
                </span>
            </li>
        `;
    });

    html += '</ul>';

    orderDetails.innerHTML = html;
    totalElement.textContent = `Total: ${savedCart.total.toFixed(2)} TND`;

    // Affiche le total avec livraison si sélectionné
    document.querySelectorAll('input[name="livraison"]').forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.checked && radio.value === 'domicile') {
                const totalWithDelivery = (savedCart.total + 7).toFixed(2);
                totalLivraisonVal.textContent = totalWithDelivery;
                totalLivraisonBlock.classList.remove('d-none');
            } else {
                totalLivraisonBlock.classList.add('d-none');
            }
        });
    });

    // Charger automatiquement les infos utilisateur (nom + adresse)
    const token = localStorage.getItem('token');

    // 🔁 Charger les infos utilisateur dans le formulaire de commande
    axios.get('/api/profile', {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    }).then(response => {
        const user = response.data;
        document.getElementById('nom').value = user.name ?? '';
        document.getElementById('adresse').value = user.adresse ?? '';
        document.getElementById('telephone').value = user.telephone ?? '';
    }).catch(error => {
        console.error('Erreur chargement utilisateur (commande)', error);
    });

    // Formulaire
    document.getElementById('order-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const nom = document.getElementById('nom').value;
        const adresse = document.getElementById('adresse').value;
        const telephone = document.getElementById('telephone').value;
        const livraison = document.querySelector('input[name="livraison"]:checked').value;

        let total = savedCart.total;
        if (livraison === 'domicile') {
            total += 7;
        }



        const payload = {
        nom,
        adresse,
        telephone,
        livraison,
        total,
        items: savedCart.items.map(item => ({
            item_id: item.item_id,
            product_id: item.product_id,
            video_id: item.video_id,
            quantity: item.quantity,
            price: item.price
        }))
    };

        function viderCart() {
            axios.delete('/api/cart', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            })
            .then(response => {
                if (response.data.success) {
                    showModal('Panier vidé avec succès!');
                    fetchCartProducts(); // Rafraîchir l'affichage du panier
                } else {
                    showModal('Erreur lors de la suppression des produits du panier.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la suppression des produits:', error);
                showModal('Erreur lors de la suppression des produits.');
            });
        }
       // Pour enregistrer la commande dans la base de données
        axios.post('/api/orders', payload, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(response => {
            if (response.data.success) {
                localStorage.removeItem('checkoutCart');
              
                window.location.href = `/invoice?order_id=${response.data.order_id}`;
                viderCart();
                showModal('Commande validée');
            } else {
                showModal('Erreur lors de la validation de la commande.');
            }
        })
        .catch(error => {
            console.error(error);
            showModal('Erreur serveur lors de la validation.');
        });
    });
});

    </script>
