<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productsContainer = document.getElementById('products');
        const loadingSpinner = document.getElementById('loading');

        function fetchProducts() {
            loadingSpinner.style.display = 'block';

            axios.get('/api/products')
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
                productsHTML += `
                    <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="rounded position-relative fruite-item p-4 border border-secondary rounded-bottom" ">
                        <div class="fruite-img">
                            <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}"
                                class="img-fluid w-100 rounded-top" alt="${product.name}">
                        </div>
                                     
                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">${product.category_name}</div>
                        <h4>${product.name}</h4>
                        <div class="d-flex justify-content-between flex-lg-wrap">
                            <p class="text-dark fs-5 fw-bold mb-0">${product.price} dt</p>
                            <a href="/products/${product.id}" class="btn btn-primary rounded-pill ">   Voir les détails      </a>
                            <button class="add-to-cart-btn btn border border-secondary rounded-pill text-primary fa fa-shopping-bag me-2" data-product-id="${product.id}">   Ajouter au panier</button>
                        </div>
                    </div>
                    </div>
                `;
            });

            productsContainer.innerHTML = productsHTML;

            // Ajouter un gestionnaire d'événements pour chaque bouton
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = button.getAttribute('data-product-id');
                    addToCart(productId);
                });
            });
        }

        function addToCart(productId) {
            const token = localStorage.getItem('token');

            // Vérifier si le jeton est défini
            if (!token) {
                console.error('Token not found in localStorage');
                alert('Vous devez être connecté pour ajouter des produits au panier.');
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
                    alert('Produit ajouté au panier avec succès!');
                } else {
                    alert('Erreur lors de l\'ajout du produit au panier.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'ajout du produit au panier:', error);
                alert('Erreur lors de l\'ajout du produit au panier.');
            });
        }

        fetchProducts();
    });
    </script>
