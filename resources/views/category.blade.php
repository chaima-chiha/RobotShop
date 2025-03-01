@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Products in Category <span id="category_id"></span></h2>

        <ul id="products-list"></ul>

        <p id="loading" style="display: none;">Loading...</p>
        <p id="error" style="color: red; display: none;">Failed to load products.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let category_id = document.getElementById('category_id').textContent;
            let productsList = document.getElementById('products-list');
            let loading = document.getElementById('loading');
            let error = document.getElementById('error');

            // Afficher le chargement
            loading.style.display = 'block';

            axios.get("{{ route('products.byCategory', ['category_id' => " + category_id + "]) }}")

                .then(response => {
                    if (response.data.success) {
                        let products = response.data.data;

                        // Masquer le chargement
                        loading.style.display = 'none';

                        if (products.length === 0) {
                            productsList.innerHTML = "<li>No products found in this category.</li>";
                        } else {
                            products.forEach(product => {
                                let li = document.createElement('li');
                                li.textContent = `${product.name} - ${product.price} $`;
                                productsList.appendChild(li);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error("Error fetching products:", error);
                    loading.style.display = 'none';
                    error.style.display = 'block';
                });
        });
    </script>
@endsection
