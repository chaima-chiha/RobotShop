

<script>
document.addEventListener('DOMContentLoaded', function () {
    const promoProductsContainer = document.getElementById('products-promo');
    const loadingPromo = document.getElementById('loading');

    function fetchPromoProducts() {
        loadingPromo.style.display = 'block';

        axios.get('/api/products', {
            params: { filter: 'promo' }
        })
        .then(response => {
            if (response.data.success) {
                const promoProducts = response.data.data.slice(0, 4);
                displayPromoProducts(promoProducts);
            } else {
                promoProductsContainer.innerHTML = '<p>Erreur lors du chargement des produits en promotion.</p>';
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des produits en promotion:', error);
            promoProductsContainer.innerHTML = '<p>Erreur lors du chargement des produits en promotion.</p>';
        })
        .finally(() => {
            loadingPromo.style.display = 'none';
        });
    }

    function displayPromoProducts(products) {
        if (products.length === 0) {
            promoProductsContainer.innerHTML = '<p>Aucun produit en promotion trouvé.</p>';
            return;
        }

        let productsHTML = '';
        products.forEach(product => {
            const isPromoted = product.is_promoted;
            const originalPrice = `${product.price} dt`;
            const discountedPrice = (product.price - (product.price * product.promotion / 100)).toFixed(2) + ' dt';

            productsHTML += `
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="card shadow-sm h-100 rounded product-card position-relative">
                        <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}"
                            class="card-img-top rounded-top" alt="${product.name}" loading="lazy">

                        <div>
                            ${isPromoted ? `<span class="badge badge-promo bg-warning text-dark">Promo-${product.promotion}%</span>` : ''}
                            ${product.is_new ? `<span class="badge badge-new bg-primary text-dark">Nouveau</span>` : ''}
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between">

                                <div>
                                <h5 class="card-title fw-bold">${product.name}</h5>
                                </div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="card-text text-muted mb-2">
                                    ${isPromoted
                                        ? `<span class="text-muted text-decoration-line-through">${originalPrice}</span><br>
                                           <span class="text-success fw-bold">${discountedPrice}</span>`
                                        : `<span class="text-success fw-bold">${originalPrice}</span>`
                                    }
                                </div>

                                  <div>
                            ${product.stock > 0
                    ? `<span class="    m-2">En stock</span>`
                    : `<span class="   m-2">Épuisé</span>`}
                    </div>
                            </div>
                            <div class="justify-content-between align-items-center mt-auto">
                                <div class="buttons">
                                    <a href="/products/${product.id}" class="btn btn-link p-0">
                                        <i class="fas fa-eye fa-lg text-primary"></i> Voir
                                    </a>
                                    <button class="btn btn-buy rounded-pill add-to-cart-btn" data-product-id="${product.id}">
                                        <i class="fa fa-shopping-bag"></i> Acheter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        promoProductsContainer.innerHTML = productsHTML;

        // Ajouter un lien à la fin
        promoProductsContainer.innerHTML += `
    <div class="col-12 text-end mt-3">
        <a href="/products?filter=promo" class="btn btn-outline-success">
            Voir plus <i class="fas fa-arrow-right"></i>
        </a>
    </div>
`;

        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function () {
                const productId = button.getAttribute('data-product-id');
                addToCart(productId);
            });
        });
    }

    function addToCart(productId) {
        const token = localStorage.getItem('token');

        if (!token) {
            showModal('Vous devez être connecté pour ajouter des produits au panier.');
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
                showModal('Produit ajouté au panier avec succès!');
            } else {
                showModal('Erreur lors de l\'ajout du produit au panier.');
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout du produit au panier:', error);
            showModal('Erreur lors de l\'ajout du produit au panier.');
        });
    }

    // Lancer le chargement automatique
    fetchPromoProducts();
});
</script>
