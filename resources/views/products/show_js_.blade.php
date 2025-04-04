

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
                   <button id="add-to-cart-btn" class="btn border border-secondary rounded-pill  text-primary fa fa-shopping-bag me-2 ">   Ajouter au panier</button>
                    <a href="/" class="btn btn-secondary">Retour</a>
                </div>
            </div>
        `;

        // Ajouter un gestionnaire d'événements pour le bouton
        const addToCartButton = document.getElementById('add-to-cart-btn');
        addToCartButton.addEventListener('click', function () {
            addToCart(product.id);
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


    fetchProductDetails(productId);
});
</script>




