    <!-- Modal Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content rounded-0 border-0 p-4">
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <input type="search" id="searchInput" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                            alert('Aucun résultat trouvé.');
                        }
                    } else {
                        alert('Erreur lors de la recherche.');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                    alert('Erreur lors de la recherche.');
                });
        }
    });
    </script>

    <!-- Modal Search End -->
