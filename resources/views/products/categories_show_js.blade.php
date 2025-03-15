<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productsContainer = document.getElementById('products');
        const loadingSpinner = document.getElementById('loading');
        const categoryName = document.getElementById('categoryName');
       // const categoryDescription = document.getElementById('categoryDescription');

        // Récupérer l'ID de la catégorie depuis l'URL
        const categoryId = window.location.pathname.split('/')[2];

        // Fonction pour charger les produits de la catégorie
        function fetchProductsByCategory(categoryId) {
            loadingSpinner.style.display = 'block';

            axios.get(`/api/categories/${categoryId}/products`)
                .then(response => {
                    console.log('Réponse API:', response.data);

                    if (response.data.success) {
                        // Supposons que la catégorie soit le premier élément du tableau
                        const category = response.data.data[0].category_name;

                        if (!category) {
                            throw new Error("Aucune catégorie trouvée !");
                        }

                        categoryName.textContent = `Produits de la catégorie : ${category}`;
                       // categoryDescription.textContent = category.description;

                        // Supposons que les produits soient aussi dans "data"
                        displayProducts(response.data.data);
                    } else {
                        productsContainer.innerHTML = '<p>Erreur lors du chargement.</p>';
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

        // Fonction pour afficher les produits
        function displayProducts(products) {
            if (products.length === 0) {
                productsContainer.innerHTML = '<p>Aucun produit trouvé dans cette catégorie.</p>';
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

                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text"><strong>Prix :</strong> ${product.price} dt</p>
                                <a href="/products/${product.id}" class="btn btn-primary">Voir les détails</a>
                                <button style="margin-Top:5px;" class="add-to-cart-btn btn border border-secondary rounded-pill text-primary fa fa-shopping-bag me-2" data-product-id="${product.id}">Ajouter au panier</button>
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

        // Charger les produits de la catégorie
        fetchProductsByCategory(categoryId);
    });
    </script>
