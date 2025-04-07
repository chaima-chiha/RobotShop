<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productsContainer = document.getElementById('products-promo-container');
        const loadingSpinner = document.getElementById('loading');

        function fetchPromoProducts() {
            loadingSpinner.style.display = 'block';

            axios.get('/api/promotion')
                .then(response => {
                    if (response.data.success) {
                        displayPromoProducts(response.data.data);
                    } else {
                        productsContainer.innerHTML = '<p>Aucun produit en promotion pour le moment.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    productsContainer.innerHTML = '<p>Erreur lors du chargement des promotions.</p>';
                })
                .finally(() => {
                    loadingSpinner.style.display = 'none';
                });
        }

        function displayPromoProducts(products) {
            if (products.length === 0) {
                productsContainer.innerHTML = '<p>Aucun produit en promotion pour le moment.</p>';
                return;
            }

            let productsHTML = '';
            products.forEach(product => {
                const newPrice = product.price ? (product.price * (1 - product.promotion/100)).toFixed(2) : product.price;

                productsHTML += `
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body position-relative">
                                <div class="fruite-img">
                                    <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}"
                                        class="img-fluid w-100 rounded-top" alt="${product.name}">
                                </div>
                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                                    ${product.category_name}
                                </div>
                                <div class="text-white bg-danger px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;">
                                    -${product.promotion}%
                                </div>

                                <h5 class="card-title mt-3">${product.name}</h5>

                                <div class="d-flex align-items-center mb-2">
                                    ${product.price ? `
                                        <span class="text-danger fs-5 fw-bold">${newPrice} dt</span>
                                        <span class="text-muted text-decoration-line-through ms-2">${product.price} dt</span>
                                    ` : `
                                        <span class="text-danger fs-5 fw-bold">${newPrice} dt</span>
                                    `}
                                </div>

                                <a href="/products/${product.id}" class="btn btn-primary btn-sm" style="margin:5px 0">
                                    Voir les détails
                                </a>
                                <button class="add-to-cart-btn btn btn-sm border border-secondary rounded-pill text-primary"
                                        data-product-id="${product.id}">
                                    <i class="fa fa-shopping-bag me-1"></i>Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            productsContainer.innerHTML = productsHTML;

            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    addToCart(productId);
                });
            });
        }

        function addToCart(productId) {
            const token = localStorage.getItem('token');
            if (!token) {
                showModal('Veuillez vous connecter pour ajouter des articles au panier.');
                return;
            }

            axios.post('/api/cart', {
                product_id: productId,
                quantity: 1
            }, {
                headers: { 'Authorization': `Bearer ${token}` }
            })
            .then(response => {
                if (response.data.success) {
                    showModal('Produit ajouté au panier!');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showModal('Erreur lors de l\'ajout au panier');
            });
        }


        fetchPromoProducts();
    });
    </script>
