@extends('layouts.application')

@section('content')
    <h1>Liste des Produits</h1>

    <input type="text" id="search" placeholder="Rechercher un produit..." class="form-control mb-3">

    <div id="loading" style="text-align: center;">
        <img src="/images/spinner.gif" alt="Chargement..." width="50">
    </div>

    <div id="products" class="row"></div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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
                        <div class="col-md-4">
                            <div class="card product-card"  style="width: 18rem" onclick="window.location.href='/products/${product.id}'">
                                <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}" class="card-img-top" alt="${product.name}">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text">${product.price} €</p>
                                     <button class="add-to-cart" data-id="${product.id}" data-name="${product.name}" data-price="${product.price}">Ajouter au panier</button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                productsContainer.innerHTML = productsHTML;
            }

            fetchProducts();
        });
    </script>
@endsection
