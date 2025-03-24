<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productsContainer = document.getElementById('products-new');
        const loadingSpinner = document.getElementById('loading');

        // Fonction pour charger les nouveaux produits
        function fetchNewProducts() {
            loadingSpinner.style.display = 'block';

            axios.get('/api/recent-products')
                .then(response => {
                    console.log('Réponse API:', response.data);

                    if (response.data.success) {
                        displayNewProducts(response.data.data);
                    } else {
                        productsContainer.innerHTML = '<p>Erreur lors du chargement des nouveaux produits.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des nouveaux produits:', error);
                    productsContainer.innerHTML = '<p>Erreur lors du chargement des nouveaux produits.</p>';
                })
                .finally(() => {
                    loadingSpinner.style.display = 'none';
                });
        }

        // Fonction pour afficher les nouveaux produits
        function displayNewProducts(products) {
            if (products.length === 0) {
                productsContainer.innerHTML = '<p>Aucun nouveau produit trouvé.</p>';
                return;
            }

            let productsHTML = '';
            products.forEach(product => {
                productsHTML += `
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="fruite-img">
                                    <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}"
                                        class="img-fluid w-100 rounded-top" alt="${product.name}">
                                </div>
                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">${product.category_name}</div>
                                <div class="text-white bg-danger px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;">Nouveau</div>
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text"><strong>Prix :</strong> ${product.price} dt</p>
                                <a href="/products/${product.id}" class="btn btn-primary" style="margin:10px">Voir les détails</a>
                                <button style="margin-Top:5px;" class="add-to-cart-btn btn border border-secondary rounded-pill text-primary fa fa-shopping-bag me-2"
                                        data-product-id="${product.id}">
                                    Ajouter au panier
                                </button>
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

     
        fetchNewProducts();
    });
</script>
