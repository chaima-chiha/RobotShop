
<script>
    document.addEventListener("DOMContentLoaded", function () {
        updateAuthMenu();
    });

    // Fonction pour mettre à jour le menu selon l'état de connexion
    function updateAuthMenu() {
        const authMenu = document.getElementById("auth-menu");
        const token = localStorage.getItem("token");

        if (token) {
            // L'utilisateur est connecté
            authMenu.innerHTML = `
                <li><a class="dropdown-item" href="/profil"><i class="fas fa-user-circle me-2"></i> Mon compte</a></li>
                <li><a class="dropdown-item" href="/mes-commandes"><i class="fas fa-box text-success me-2"></i> Mes commandes</a></li>
                 <li><a class="dropdown-item" href="/mes-videos-viewees"><i class="fas fa-video text-success me-2"></i> Mes videos</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/" id="logout">
                    <i class="fas fa-sign-out-alt me-2"></i> Se déconnecter
                </a></li>
            `;
        } else {
            // L'utilisateur N'EST PAS connecté
            authMenu.innerHTML = `
                <li><a class="dropdown-item text-success" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                </a></li>
            `;
        }
    }
    document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", function (event) {
        if (event.target.closest("#logout")) {
            event.preventDefault(); // Empêche le lien de recharger la page

            const token = localStorage.getItem("token");

            if (!token) {
                console.error("Aucun token trouvé, l'utilisateur n'est pas connecté.");
                return;
            }

            axios.post('/api/logout', {}, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(() => {
                localStorage.removeItem("token"); // Supprimer le token
                window.location.href = "/"; // Redirection vers la page d'accueil
            })
            .catch(error => console.error("Erreur lors de la déconnexion :", error));
        }
    });
});


    </script>
