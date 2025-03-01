<script>
document.addEventListener('DOMContentLoaded', function () {
    const productsContainer = document.getElementById('products');
    const loadingSpinner = document.getElementById('loading');
    const categoriesDropdown = document.getElementById('categories-dropdown');

    // Obtenir la catégorie à partir de l'URL (si elle existe)
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category');

    // Fonction pour charger les catégories
    function fetchCategories() {
        axios.get('/api/categories')
            .then(response => {
                if (response.data.success) {
                    displayCategories(response.data.data);
                } else {
                    console.error('Erreur lors du chargement des catégories.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des catégories:', error);
            });
    }

    // Fonction pour afficher les catégories dans le menu déroulant
    function displayCategories(categories) {
        let categoriesHTML = '<li><a class="dropdown-item" href="/products">Toutes les catégories</a></li>';

        categories.forEach(category => {
            categoriesHTML += `
                <li><a class="dropdown-item" href="/products?category=${category.id}">${category.name}</a></li>
            `;
        });

        categoriesDropdown.innerHTML = categoriesHTML;
    }

    // Fonction pour charger les produits (avec ou sans filtre par catégorie)
    function fetchProducts() {
        loadingSpinner.style.display = 'block';

        let url = '/api/products';
        if (categoryId) {
            url += `?category=${categoryId}`;
        }

        axios.get(url)
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

    // Fonction pour afficher les produits
    function displayProducts(products) {
        if (products.length === 0) {
            productsContainer.innerHTML = '<p>Aucun produit trouvé.</p>';
            return;
        }

        let productsHTML = '';
        products.forEach(product => {
            productsHTML += `
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="rounded position-relative fruite-item p-4 border border-secondary rounded-bottom" onclick="window.location.href='/products/${product.id}'">
                        <div class="fruite-img">
                            <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}"
                                class="img-fluid w-100 rounded-top" alt="${product.name}">
                        </div>
                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">${product.category ? product.category.name : 'Sans catégorie'}</div>
                        <h4>${product.name}</h4>
                        <div class="d-flex justify-content-between flex-lg-wrap">
                            <p class="text-dark fs-5 fw-bold mb-0">${product.price} dt</p>
                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"
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

    // Mettre à jour le titre si une catégorie est sélectionnée
    function updateCategoryTitle() {
        if (categoryId) {
            axios.get(`/api/categories/${categoryId}`)
                .then(response => {
                    if (response.data.success) {
                        const categoryName = response.data.data.name;
                        document.querySelector('h1').textContent = `Produits: ${categoryName}`;
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération du nom de la catégorie:', error);
                });
        }
    }

    // Initialiser le chargement des données
    fetchCategories();
    fetchProducts();
    if (categoryId) {
        updateCategoryTitle();
    }
});
</script>
