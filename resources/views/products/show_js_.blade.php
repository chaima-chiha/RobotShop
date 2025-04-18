<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productId = window.location.pathname.split('/').pop();
        const productContainer = document.getElementById('product-details');
        const alertContainer = document.getElementById('alert-containerShow');

        function fetchProductDetails(id) {
            axios.get(`/api/products/${id}`)
                .then(response => {
                    if (response.data.success) {
                        displayProduct(response.data.data);
                    } else {
                        productContainer.innerHTML = '<p class="text-danger">Produit non trouvé.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération du produit:', error);
                    productContainer.innerHTML = '<p class="text-danger">Erreur lors du chargement du produit.</p>';
                });
        }

        function displayProduct(product) {
            const originalPrice = `${product.price} dt`;
            const discountedPrice = product.promotion > 0
                ? (product.price - (product.price * product.promotion / 100)).toFixed(2) + ' dt'
                : null;

            productContainer.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}"
                             class="img-fluid rounded" alt="${product.name}">
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            ${product.is_promoted ? `<span class="badge bg-warning text-dark me-2">Promo -${product.promotion}%</span>` : ''}
                            ${product.is_new ? `<span class="badge bg-primary text-white">Nouveau</span>` : ''}
                        </div>

                        <h1 class="mt-2">${product.name}</h1>
                        <p class="text-muted">${product.category_name ?? 'Aucune catégorie'}</p>

                        <div class="mb-3">
                            ${discountedPrice
                                ? `<div><span class="text-decoration-line-through text-muted">${originalPrice}</span></div>
                                   <div><span class="text-success fw-bold">${discountedPrice}</span></div>`
                                : `<div><span class="text-success fw-bold">${originalPrice}</span></div>`
                            }
                        </div>

                        <div class="mb-3">
                            <span class="${product.available_stock > 0 ? 'text-success' : 'text-danger'} fw-bold">
                                ${product.available_stock > 0 ? 'En stock (' + product.available_stock + ')' : 'Épuisé'}
                            </span>
                        </div>

                        <p>${product.description || 'Aucune description disponible.'}</p>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <button id="add-to-cart-btn" class="btn border border-secondary rounded-pill text-primary">
                                <i class="fa fa-shopping-bag me-2"></i> Acheter
                            </button>
                            <a href="/" class="btn btn-secondary rounded-pill">Retour</a>
                        </div>
                    </div>
                </div>
            `;

            const addToCartButton = document.getElementById('add-to-cart-btn');
            addToCartButton.addEventListener('click', function () {
                addToCart(product.id, product.available_stock);
            });
        }

        function addToCart(productId, stock) {
            const token = localStorage.getItem('token');

            if (!token) {
                showModal('Vous devez être connecté pour ajouter des produits au panier.', 'warning');
                return;
            }

            if (stock <= 0) {
                showModal('Produit épuisé.', 'warning');
                return;
            }

            axios.post('/api/cart', {
                product_id: productId,
                quantity: 1
            }, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                if (response.data.success) {
                    showModal('Produit ajouté au panier avec succès!', 'success');
                    updateCartCount();
                } else {
                    showModal('Erreur lors de l\'ajout du produit au panier.', 'danger');
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'ajout du produit au panier:', error);
                showModal('Erreur lors de l\'ajout du produit au panier.', 'danger');
            });
        }

        function updateCartCount() {
            const token = localStorage.getItem('token');

            axios.get('/api/cart', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(res => {
                if (res.data.success) {
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = res.data.data.length;
                    }
                }
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour du panier:', error);
            });
        }
        fetchProductDetails(productId);
    });
    </script>
