@extends('layouts.application')

@section('content')

        <a href="{{ url('/products') }}" >⬅ Retour à la liste</a>

        <div id="product-details"></div>

        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const productId = window.location.pathname.split('/').pop();
                const productDetailsContainer = document.getElementById('product-details');

                function fetchProduct() {
                    axios.get(`/api/products/${productId}`)
                        .then(response => {
                            if (response.data.success) {
                                displayProduct(response.data.data);
                            } else {
                                productDetailsContainer.innerHTML = '<p>Produit non trouvé.</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Erreur lors de la récupération du produit:', error);
                            productDetailsContainer.innerHTML = '<p>Erreur lors du chargement du produit.</p>';
                        });
                }

                function displayProduct(product) {
                    productDetailsContainer.innerHTML = `
                        <div class="card">
                            <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h2 class="card-title">${product.name}</h2>
                                <p><strong>Référence :</strong> ${product.refrence}</p>
                                <p><strong>Description :</strong> ${product.description}</p>
                                <p><strong>Prix :</strong> ${product.price} €</p>
                                <p><strong>Disponibilité :</strong> ${product.stock > 0 ? '✅ Disponible' : '❌ Non disponible'}</p>
                                <p><strong>Catégorie :</strong> ${product.category.name}</p>
                                 <button class="add-to-cart" data-id="${product.id}" data-name="${product.name}" data-price="${product.price}">Ajouter au panier</button>
                            </div>
                        </div>
                    `;
                }

                fetchProduct();
            });
        </script>

@endsection
