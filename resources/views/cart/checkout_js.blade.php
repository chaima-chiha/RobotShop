<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cartTableBody = document.getElementById('cart-table-body');
        const loadingSpinner = document.getElementById('loading');
        const printOrderBtn = document.getElementById('print-order-btn');
        const validateOrderBtn = document.getElementById('pay-order-btn');
        const cartSubtotal = document.getElementById('cart-subtotal');
        const cartTax = document.getElementById('cart-tax');
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
                    cartTableBody.innerHTML = '<tr><td colspan="5">Erreur lors du chargement du panier.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des produits du panier:', error);
                cartTableBody.innerHTML = '<tr><td colspan="5">Erreur lors du chargement du panier.</td></tr>';
            })
            .finally(() => {
                loadingSpinner.style.display = 'none';
            });
        }

        function displayCartProducts(products) {
            if (products.length === 0) {
                cartTableBody.innerHTML = '<tr><td colspan="5">Votre panier est vide.</td></tr>';
                return;
            }

            let cartProductsHTML = '';
            let subtotal = 0;
            products.forEach(cartItem => {
                const product = cartItem.product;
                const price = parseFloat(product.price);
                const itemTotal = price * cartItem.quantity;
                subtotal += itemTotal;
                cartProductsHTML += `
                    <tr>
                        <td>${product.name}</td>
                        <td>${product.description || ''}</td>
                        <td>${cartItem.quantity}</td>
                        <td>${price.toFixed(2)} D</td>
                        <td>${itemTotal.toFixed(2)} D</td>
                    </tr>
                `;
            });

            cartTableBody.innerHTML = cartProductsHTML;
            updateCartTotals(subtotal);
        }

        function updateCartTotals(subtotal) {
            const tax = subtotal * 0.1; // Example tax calculation
            const total = subtotal + tax;
            cartSubtotal.textContent = `$${subtotal.toFixed(2)}`;
            cartTax.textContent = `$${tax.toFixed(2)}`;
            cartTotal.textContent = `$${total.toFixed(2)}`;
        }

        validateOrderBtn.addEventListener('click', function () {
            window.location.href = '/payment';
        });

        printOrderBtn.addEventListener('click', function () {
            window.print();
        });

        fetchCartProducts();
    });
    </script>
