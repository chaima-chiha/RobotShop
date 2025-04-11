


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

    function displayCartProducts(products) {
        if (products.length === 0) {
            cartTableBody.innerHTML = '<tr><td colspan="6">Votre panier est vide.</td></tr>';
            return;
        }

        let cartProductsHTML = '';
        let subtotal = 0;
        products.forEach(cartItem => {
            const product = cartItem.product;
            const itemTotal = product.price * cartItem.quantity;
            subtotal += itemTotal;
            cartProductsHTML += `
                <tr>
                    <th scope="row">
                        <div class="d-flex align-items-center">
                            <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}"
                                class="img-fluid me-5 rounded-circle product-image" style="width: 80px; height: 80px;" alt="${product.name}">
                        </div>
                    </th>
                    <td>
                        <p class="mb-0 mt-4 product-name">${product.name}</p>
                    </td>
                    <td>
                        <p class="mb-0 mt-4">${product.price} D</p>
                    </td>
                    <td>
                        <div class="input-group quantity mt-4" style="width: 100px;">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-minus rounded-circle bg-light border" data-product-id="${product.id}">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center border-0 quantity-input" value="${cartItem.quantity}" readonly>
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-plus rounded-circle bg-light border" data-product-id="${product.id}">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="mb-0 mt-4">${(product.price * cartItem.quantity).toFixed(2)} D</p>
                    </td>
                    <td>
                        <button class="btn btn-md rounded-circle bg-light border mt-4 remove-from-cart-btn" data-product-id="${product.id}">
                            <i class="fa fa-times text-danger"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        cartTableBody.innerHTML = cartProductsHTML;
        updateCartTotals(subtotal);

        // Add event listeners for the remove and quantity buttons
        addEventListeners();
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
//boutton passer la commande
    validateOrderBtn.addEventListener('click', function () {
    const cartItems = [];
    document.querySelectorAll('#cart-table-body tr').forEach(row => {
        const productId = row.querySelector('.remove-from-cart-btn').getAttribute('data-product-id');
        const quantity = parseInt(row.querySelector('.quantity-input').value);
        const price = parseFloat(row.querySelector('td:nth-child(3)').textContent.replace(' D', ''));

        const name = row.querySelector('.product-name')?.textContent.trim();
        const image = row.querySelector('.product-image')?.getAttribute('src');

        cartItems.push({
            product_id: productId,
            name: name,
            image: image,
            quantity: quantity,
            price: price
        });
    });

    const total = parseFloat(cartTotal.textContent.replace('Dt', ''));

    // Stocker dans localStorage
    localStorage.setItem('checkoutCart', JSON.stringify({ items: cartItems, total }));

    // Rediriger vers la page de confirmation
    window.location.href = '/order-confirmation';


});


    function addEventListeners() {
        document.querySelectorAll('.remove-from-cart-btn').forEach(button => {
            button.addEventListener('click', function () {
                const productId = button.getAttribute('data-product-id');
                removeFromCart(productId);
            });
        });

        document.querySelectorAll('.btn-minus, .btn-plus').forEach(button => {
    button.addEventListener('click', function () {
        const productId = button.getAttribute('data-product-id');
        const quantityInput = button.closest('.quantity').querySelector('.quantity-input');
        let quantity = parseInt(quantityInput.value);

        if (button.classList.contains('btn-plus')) {
            const maxStock = parseInt(button.getAttribute('data-stock'));
            if (quantity < maxStock) {
                quantity += 1;
            } else {
                showModal('Stock insuffisant pour ce produit.', 'warning');
                return;
            }
        } else if (button.classList.contains('btn-minus') && quantity > 1) {
            quantity -= 1;
        }

        quantityInput.value = quantity;
        updateCartQuantity(productId, quantity);
    });
});

    }

    function removeFromCart(productId) {
        axios.delete(`/api/cart/${productId}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(response => {
            if (response.data.success) {
                showModal('Produit retiré du panier avec succès!');
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

    function updateCartQuantity(productId, quantity) {
        axios.put(`/api/cart/${productId}`, { quantity: quantity }, {
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
