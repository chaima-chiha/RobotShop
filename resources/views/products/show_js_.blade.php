
<script>
document.addEventListener('DOMContentLoaded', function () {
    const productId = window.location.pathname.split('/').pop();
    const productContainer = document.getElementById('product-details');

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
        productContainer.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}"
                         class="img-fluid rounded" alt="${product.name}">
                </div>
                <div class="col-md-6">
                    <h1>${product.name}</h1>
                    <p class="text-muted">${product.category ? product.category.name : 'Aucune catégorie'}</p>
                    <h3 class="text-primary">${product.price} dt</h3>
                    <p>${product.description || 'Aucune description disponible.'}</p>
                    <button id="addToCartBtn" class="btn btn-primary">Ajouter au panier</button>
                    <a href="/" class="btn btn-secondary">Retour</a>
                </div>
            </div>
        `;

        // Ajouter un événement au bouton "Ajouter au panier"
        document.getElementById('addToCartBtn').addEventListener('click', function () {
            addToCart(product);
        });
    }

    function addToCart(product) {
        axios.post('/api/cart/add', {
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: 1
        })
        .then(response => {
            alert('Produit ajouté au panier !');
            console.log(response.data);
        })
        .catch(error => {
            console.error('Erreur lors de l\'ajout au panier:', error);
        });
    }

    fetchProductDetails(productId);
});


    </script>

