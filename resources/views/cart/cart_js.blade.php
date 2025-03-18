<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cartTableBody = document.getElementById('cart-table-body');
        const loadingSpinner = document.getElementById('loading');
        const validateOrderBtn = document.getElementById('validate-order-btn');
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
                    console.log(response.data.data)
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
                            class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="${product.name}">
                    </div>
                </th>
                <td>
                    <p class="mb-0 mt-4">${product.name}</p>
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

    function updateCartTotals(total) {

        cartTotal.textContent = `${total.toFixed(2)}Dt`;
    }

    validateOrderBtn.addEventListener('click', function () {
        // order validation logic
        window.location.href = '/checkout';
        alert('Order validated!');
    });

    // Ajouter un gestionnaire d'événements pour chaque bouton de suppression
    document.querySelectorAll('.remove-from-cart-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = button.getAttribute('data-product-id');
            removeFromCart(productId);
        });
    });

    // Ajouter des gestionnaires d'événements pour les boutons "moins" et "plus"
    document.querySelectorAll('.btn-minus, .btn-plus').forEach(button => {
        button.addEventListener('click', function () {
            const productId = button.getAttribute('data-product-id');
            const quantityInput = button.closest('.quantity').querySelector('.quantity-input');
            let quantity = parseInt(quantityInput.value);

            if (button.classList.contains('btn-plus')) {
                quantity += 1;
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
                    alert('Produit retiré du panier avec succès!');
                    fetchCartProducts(); // Recharger les produits du panier
                } else {
                    alert('Erreur lors de la suppression du produit du panier de 1.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la suppression du produit du panier:', error);
                alert('Erreur lors de la suppression du produit du panier.');
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
            fetchCartProducts(); // Recharger les produits du panier pour mettre à jour l'affichage
        } else {
            alert('Erreur lors de la mise à jour de la quantité du produit.');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la mise à jour de la quantité du produit:', error);
        alert('Erreur lors de la mise à jour de la quantité du produit.');
    });
}


        fetchCartProducts();
    });
    </script>
