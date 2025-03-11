<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productsContainer = document.getElementById('products');
        const loadingSpinner = document.getElementById('loading');
        const categoryName = document.getElementById('categoryName');
        const categoryDescription = document.getElementById('categoryDescription');

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
                            const category = response.data.data[0];

                            if (!category) {
                                throw new Error("Aucune catégorie trouvée !");
                            }

                            categoryName.textContent = `Produits de la catégorie : ${category.name}`;
                            categoryDescription.textContent = category.description;

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
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text"><strong>Prix :</strong> ${product.price} dt</p>
                                <a href="/products/${product.id}" class="btn btn-primary">Voir les détails</a>
                                 <a href="#" style="margin-Top:5px;" class="btn border border-secondary rounded-pill px-3 text-primary "
                                        data-id="${product.id}"
                                        data-name="${product.name}"
                                        data-price="${product.price}">
                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                        </a>
                            </div>
                        </div>
                    </div>
                `;
            });

            productsContainer.innerHTML = productsHTML;
        }

        // Charger les produits de la catégorie
        fetchProductsByCategory(categoryId);
    });
</script>
