
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cartTableBody = document.getElementById('cart-table-body');
    const loadingSpinner = document.getElementById('loading');
    const validateOrderBtn = document.getElementById('validate-order-btn');
    const viderCartBtn = document.getElementById('vider-cart-btn');
    const cartTotal = document.getElementById('cart-total');

    function fetchCartProducts() {
        loadingSpinner.style.display = 'block';

        axios.get('/api/cart', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(response => {
            if (response.data.success) {
                displayCartProducts(response.data.data);
            } else {
                cartTableBody.innerHTML = '<tr><td colspan="6">Erreur lors du chargement du panier.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des produits du panier:', error);
            cartTableBody.innerHTML = '<tr><td colspan="6">Erreur lors du chargement du panier.</td></tr>';
        })
        .finally(() => {
            loadingSpinner.style.display = 'none';
        });
    }

    function displayCartProducts(items) {
    if (items.length === 0) {
        cartTableBody.innerHTML = '<tr><td colspan="6">Votre panier est vide.</td></tr>';
        updateCartCount(items);
        return;
    }

    let cartHTML = '';
    let subtotal = 0;

    items.forEach(item => {
        const product = item.product;
        const video = item.video;

        const idproduct = item.product_id;
        const idvideo = item.video_id;
        const type = product ? 'Produit' : (video ? 'Vidéo' : 'Inconnu');
        const name = product?.name || video?.title || 'N/A';
        const image = product?.image
            ? `/storage/${product.image}`
            : (video?.thumbnail ? `/storage/${video.thumbnail}` : '/images/default.png');
        const price = product?.price
            ? (product.price * (1 - (product.promotion / 100)))
            : (video?.price || 0);

        const quantity = item.quantity;
        const total = price * quantity;
        subtotal += total;

        const stock = product?.available_stock || null;

        cartHTML += `
            <tr>
                <th scope="row">
                    <div class="d-flex align-items-center">
                        <img src="${image}" class="img-fluid me-5 align-items-center rounded-circle product-image" style="width: 80px; height: 80px;" alt="${name}">
                    </div>
                </th>
                <td><p class="mb-0 mt-4 product-name">${name}</p></td>
                <td><p class="mb-0 mt-4">${price.toFixed(2)} D</p></td>
                <td>
                    <div class="input-group quantity mt-4" style="width: 110px;">
                        <div class="input-group-btn">
                            <button class="btn btn-sm btn-minus rounded-circle bg-light border" data-item-id="${item.id}">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control form-control-sm text-center border-0 quantity-input" value="${quantity}" readonly>
                        <div class="input-group-btn">
                            <button class="btn btn-sm btn-plus rounded-circle bg-light border" data-item-id="${item.id}" ${stock ? `data-stock="${stock}"` : ''}>
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>

                    </div>
                </td>
                <td><p class="mb-0 mt-4">${total.toFixed(2)} D</p></td>
                <td>
                    <button class="btn btn-md rounded-circle bg-light border mt-4 remove-from-cart-btn" data-item-id="${item.id}">
                        <i class="fa fa-times text-danger"></i>
                    </button>
                </td>
                <td><p class="mb-0 mt-4">${type}</p></td>
                <td class="d-none product-id">${idproduct}</td>
                <td class="d-none video-id">${idvideo}</td>
            </tr>
        `;
    });

    cartTableBody.innerHTML = cartHTML;
    updateCartTotals(subtotal);
    updateCartCount(items);
    addEventListeners(); // Ajoute les listeners de suppression et de modification de quantité
}

function updateCartTotals(total) {
    cartTotal.textContent = `${total.toFixed(2)}Dt`;
}

function viderCart() {
    axios.delete('/api/cart', {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    })
    .then(response => {
        if (response.data.success) {
            showModal('Panier vidé avec succès!');
            fetchCartProducts(); // Refresh the cart display
        } else {
            showModal('Erreur lors de la suppression des produits du panier.');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la suppression des produits:', error);
        showModal('Erreur lors de la suppression des produits.');
    });
}

    // Bouton passer la commande
validateOrderBtn.addEventListener('click', function () {
    const cartItems = [];
    document.querySelectorAll('#cart-table-body tr').forEach(row => {
        const removeBtn = row.querySelector('.remove-from-cart-btn');
        if (!removeBtn) return;

        const itemId = removeBtn.getAttribute('data-item-id');
        const quantity = parseInt(row.querySelector('.quantity-input').value);
        const price = parseFloat(row.querySelector('td:nth-child(3)').textContent.replace(' D', ''));

        const name = row.querySelector('.product-name')?.textContent.trim();
        const image = row.querySelector('.product-image')?.getAttribute('src');

        const productIdText = row.querySelector('.product-id')?.textContent.trim();
        const videoIdText = row.querySelector('.video-id')?.textContent.trim();

        const productId = productIdText ? parseInt(productIdText) : null;
        const videoId = videoIdText && videoIdText !== 'null' ? parseInt(videoIdText) : null;


        cartItems.push({
            item_id: itemId,
            product_id: productId,
            video_id: videoId,
            name: name,
            image: image,
            quantity: quantity,
            price: price
        });
    });

    if (cartItems.length === 0) {
        showModal('🛒 Votre panier est vide, vous ne pouvez pas passer la commande.');
        return; // Stoppe l'exécution ici
    }

    const total = parseFloat(cartTotal.textContent.replace('Dt', ''));

    // Stocker dans localStorage
    localStorage.setItem('checkoutCart', JSON.stringify({ items: cartItems, total }));

    // Rediriger vers la page de confirmation
    window.location.href = '/order-confirmation';
});

    function addEventListeners() {
        document.querySelectorAll('.remove-from-cart-btn').forEach(button => {
            button.addEventListener('click', function () {
                const itemId = button.getAttribute('data-item-id');
                removeFromCart(itemId);
            });
        });

        document.querySelectorAll('.btn-minus, .btn-plus').forEach(button => {
            button.addEventListener('click', function () {
                const itemId = button.getAttribute('data-item-id');
                const quantityInput = button.closest('.quantity').querySelector('.quantity-input');
                let quantity = parseInt(quantityInput.value);

                if (button.classList.contains('btn-plus')) {
                    const maxStock = button.getAttribute('data-stock');
                    if (maxStock && quantity < parseInt(maxStock)) {
                        quantity += 1;
                    } else if (!maxStock) {
                        quantity += 1;
                    } else {
                        showModal('Stock insuffisant pour ce produit.', 'warning');
                        return;
                    }
                } else if (button.classList.contains('btn-minus') && quantity > 1) {
                    quantity -= 1;
                }

                quantityInput.value = quantity;
                updateCartQuantity(itemId, quantity);
            });
        });
    }

    function removeFromCart(itemId) {
        axios.delete(`/api/cart/${itemId}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(response => {
            if (response.data.success) {
                fetchCartProducts();
            } else {
                showModal('Erreur lors de la suppression du produit du panier.');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la suppression du produit du panier:', error);
            showModal('Erreur lors de la suppression du produit du panier.');
        });
    }

    function updateCartQuantity(itemId, quantity) {
        axios.put(`/api/cart/${itemId}`, { quantity: quantity }, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(response => {
            if (response.data.success) {
                fetchCartProducts();
            } else {
                showModal('Erreur lors de la mise à jour de la quantité du produit.');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la mise à jour de la quantité du produit:', error);
            showModal('Erreur lors de la mise à jour de la quantité du produit.', 'danger');
        });
    }

    // Ensure the event listener is added after the function is defined
    viderCartBtn.addEventListener('click', viderCart);

    fetchCartProducts();
});

</script>
