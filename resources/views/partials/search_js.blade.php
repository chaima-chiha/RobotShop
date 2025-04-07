
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const searchIcon = document.getElementById('search-icon-1');

        searchIcon.addEventListener('click', function () {
            const keyword = searchInput.value.trim();
            if (keyword) {
                searchByKeyword(keyword);
            }
        });

        searchInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                const keyword = searchInput.value.trim();
                if (keyword) {
                    searchByKeyword(keyword);
                }
            }
        });

        function searchByKeyword(keyword) {
            axios.get(`/api/search?query=${keyword}`)
                .then(response => {
                    if (response.data.success) {
                        const result = response.data.data;
                        if (result.product) {
                            window.location.href = `/products/${result.product.id}`;
                        } else if (result.category) {
                            window.location.href = `/categories/${result.category.id}/products`;
                        } else {
                            showModal('Aucun résultat trouvé.');
                        }
                    } else {
                        showModal('Erreur lors de la recherche.');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                    showModal('Erreur lors de la recherche.');
                });
        }
    });
    </script>

    <!-- Modal Search End -->
