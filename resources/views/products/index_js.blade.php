<script>
document.addEventListener('DOMContentLoaded', function () {
    const productsContainer = document.getElementById('products');
    const loadingSpinner = document.getElementById('loading');
    const filterDropdown = document.getElementById('filter');
    const urlParams = new URLSearchParams(window.location.search);
    const filterParam = urlParams.get('filter');

  // Si un filtre est défini dans l'URL, on l'applique et on met à jour le dropdown
  if (filterParam) {
        filterDropdown.value = filterParam;
        fetchProducts(filterParam);
    } else {
        // Sinon, on utilise la valeur actuelle du dropdown
        const defaultFilter = filterDropdown.value || 'all';
        fetchProducts(defaultFilter);
    }

    function fetchProducts(filter = 'all', category = 'all') {
    loadingSpinner.style.display = 'block';

    axios.get('/api/products', {
        params: { filter: filter, category: category }
    })
    .then(response => {
        if (response.data.success) {
            displayProducts(response.data.data);
        } else {
            productsContainer.innerHTML = '<p>Erreur lors du chargement des produits.</p>';
        }
    })
    .catch(error => {
        console.error('Erreur lors de la récupération des produits:', error);
        productsContainer.innerHTML = '<p>Erreur lors du chargement des produits.</p>';
    })
    .finally(() => {
        loadingSpinner.style.display = 'none';
    });
}



    function displayProducts(products) {
        if (products.length === 0) {
            productsContainer.innerHTML = '<p>Aucun produit trouvé.</p>';
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
                    ? `<span class="    m-2">En stock</span>`
                    : `<span class="   m-2">Épuisé</span>`}
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

        productsContainer.innerHTML = productsHTML;

        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function () {
                const productId = button.getAttribute('data-product-id');
                const stock = parseInt(button.getAttribute('data-product-stock'));

                    if (stock <= 0) {
                        showModal('Produit insuffisant ou épuisé.','warning');
                        return;
                    }
                addToCart(productId);

            });
        });
    }



       // Quand l'utilisateur change le filtre via le dropdown
       filterDropdown.addEventListener('change', function () {
        const selectedFilter = filterDropdown.value;

        // Met à jour l'URL sans recharger la page
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('filter', selectedFilter);
        window.history.pushState({}, '', newUrl);

        // Recharge les produits avec le nouveau filtre
        fetchProducts(selectedFilter);
    });


    function addToCart(productId) {
        const token = localStorage.getItem('token');

        if (!token) {
            console.error('Token not found in localStorage');
            showModal('Vous devez être connecté pour ajouter des produits au panier.','warning');
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
                fetchProducts();
                showModal('Produit ajouté au panier avec succès!','success');

                          //  Appel à la fonction pour mettre à jour le compteur du panier
            axios.get('/api/cart', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(res => {
                if (res.data.success) {
                    updateCartCount(res.data.data);
                }
            });
            } else {
                showModal('Erreur lors de l\'ajout du produit au panier.','danger');
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout du produit au panier:', error);
            showModal('Erreur lors de l\'ajout du produit au panier.');
        });
    }





});


</script>
