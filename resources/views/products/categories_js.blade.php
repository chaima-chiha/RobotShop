

<!-- Script pour charger les catégories -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoriesMenu = document.getElementById('categoriesMenu');

        // Fonction pour charger les catégories
        function loadCategories() {
            axios.get('/api/categories')
                .then(response => {
                    if (response.data.success) {
                        const categories = response.data.data;
                        let categoriesHTML = '';

                        // Générer le HTML pour chaque catégorie
                        categories.forEach(category => {
                            categoriesHTML += `
                                <a class="dropdown-item " href="/categories/${category.id}/products">
                                    ${category.name}
                                </a>
                            `;
                        });

                        // Injecter le HTML dans le menu déroulant
                        categoriesMenu.innerHTML = categoriesHTML;
                    } else {
                        categoriesMenu.innerHTML = '<span class="dropdown-item text-white">Erreur lors du chargement des catégories.</span>';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des catégories:', error);
                    categoriesMenu.innerHTML = '<span class="dropdown-item text-white">Erreur lors du chargement des catégories.</span>';
                });
        }

        // Charger les catégories au chargement de la page
        loadCategories();
    });
</script>
