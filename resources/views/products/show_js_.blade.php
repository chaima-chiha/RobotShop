<script>
document.addEventListener('DOMContentLoaded', function () {
    const productId = window.location.pathname.split('/').pop();
    const productContainer = document.getElementById('product-details');
    const similarContainer = document.getElementById('similar-products');

    function fetchProductDetails(id) {
        axios.get(`/api/products/${id}`)
            .then(response => {
                if (response.data.success) {
                    displayProduct(response.data.data);
                    fetchSimilarProducts(response.data.data.category_id, response.data.data.id);
                } else {
                    productContainer.innerHTML = '<p class="text-danger">Produit non trouvé.</p>';
                }
            })
            .catch(error => {
                console.error('Erreur produit:', error);
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
                    <h1>${product.name}</h1>
                    <p class="text-muted">${product.category_name ?? 'Aucune catégorie'}</p>
                    <div>
                        ${discountedPrice
                            ? `<div><span class="text-decoration-line-through">${originalPrice}</span></div>
                               <div><span class="fw-bold text-success">${discountedPrice}</span></div>`
                            : `<div><span class="fw-bold text-success">${originalPrice}</span></div>`
                        }
                    </div>
                    <div class="my-3">
                        <span class="${product.available_stock > 0 ? 'text-success' : 'text-danger'} fw-bold">
                            ${product.available_stock > 0 ? 'En stock (' + product.available_stock + ')' : 'Épuisé'}
                        </span>
                    </div>
                    <p>${product.description || 'Aucune description disponible.'}</p>
                    <button id="add-to-cart-btn" class="btn btn-outline-primary rounded-pill mt-3">
                        <i class="fa fa-shopping-bag me-2"></i> Acheter
                    </button>
                    <a href="/" class="btn btn-secondary rounded-pill mt-3">Retour</a>
                </div>
            </div>
        `;

        document.getElementById('add-to-cart-btn').addEventListener('click', function () {
            addToCart(product.id, product.available_stock);
        });
    }

    function fetchSimilarProducts(categoryId, currentProductId) {
        axios.get('/api/products', {
            params: {
                category: categoryId
            }
        }).then(response => {
            if (response.data.success) {
                const similarProducts = response.data.data.filter(p => p.id != currentProductId).slice(0, 4);
                displaySimilarProducts(similarProducts);
            }
        }).catch(error => {
            console.error('Erreur produits similaires:', error);
            similarContainer.innerHTML = '<p>Erreur lors du chargement des produits similaires.</p>';
        });
    }

    function displaySimilarProducts(products) {
    if (products.length === 0) {
        similarContainer.innerHTML = '<p>Aucun produit similaire trouvé.</p>';
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

                        <div> ${product.available_stock}</div>
                        <div>
                            ${product.available_stock > 0
                                ? `<span class="m-2">En stock</span>`
                                : `<span class="m-2">Épuisé</span>`}
                        </div>
                    </div>

                    <div class="justify-content-between align-items-center mt-auto">
                        <div class="buttons">
                            <a href="/products/${product.id}" class="btn btn-link p-0">
                                <i class="fas fa-eye fa-lg text-primary"></i> Voir
                            </a>
                            <button
                                class="btn btn-buy rounded-pill add-to-cart-btn"
                                data-product-id="${product.id}"
                                data-product-stock="${product.available_stock}">
                                <i class="fa fa-shopping-bag"></i> Acheter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
    });

    similarContainer.innerHTML = productsHTML;

    // Ajouter les listeners pour les boutons "Acheter"
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = button.getAttribute('data-product-id');
            const stock = parseInt(button.getAttribute('data-product-stock'));

            if (stock <= 0) {
                showModal('Produit insuffisant ou épuisé.', 'warning');
                return;
            }

            addToCart(productId);
        });
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
                showModal('Produit ajouté au panier !', 'success');
                updateCartCount();
            } else {
                showModal('Erreur lors de l\'ajout.', 'danger');
            }
        })
        .catch(error => {
            console.error('Erreur panier:', error);
            showModal('Erreur technique.', 'danger');
        });
    }

    function updateCartCount() {
        const token = localStorage.getItem('token');
        if (!token) return;

        axios.get('/api/cart', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        }).then(res => {
            if (res.data.success) {
                const countElement = document.getElementById('cart-count');
                if (countElement) {
                    countElement.textContent = res.data.data.length;
                }
            }
        }).catch(err => {
            console.error('Erreur compteur panier:', err);
        });
    }

    fetchProductDetails(productId);
});
</script>

