<script>
window.showModal = function (message, type = 'info') {
    const modalElement = document.getElementById('alertModal');
    const modalHeader = document.getElementById('alertModalHeader');
    const modalTitle = document.getElementById('alertModalLabel');
    const modalIcon = document.getElementById('alertModalIcon');
    const modalBody = document.getElementById('alertModalMessage');

    const settings = {
        success: {
            title: 'Succès',
            bg: 'bg-success bg-opacity-25',
            icon: '<i class="fas fa-check-circle text-white"></i>'
        },
        danger: {
            title: 'Erreur',
            bg: 'bg-danger bg-opacity-25',
            icon: '<i class="fas fa-times-circle text-white"></i>'
        },
        warning: {
            title: 'Attention',
            bg: 'bg-warning bg-opacity-25',
            icon: '<i class="fas fa-exclamation-triangle text-white"></i>'
        },
        info: {
            title: 'Information',
            bg: 'bg-info bg-opacity-25',
            icon: '<i class="fas fa-info-circle text-white"></i>'
        }
    };

    const config = settings[type] || settings['info'];

    // Reset header classes
    modalHeader.className = 'modal-header align-items-center ' + config.bg;
    modalTitle.textContent = config.title;
    modalIcon.innerHTML = config.icon;
    modalBody.innerHTML = message;

    const modalInstance = new bootstrap.Modal(modalElement);
    modalInstance.show();
};



//panir count


// Fonction globale à placer en haut ou en dehors du DOMContentLoaded
function updateCartCount(products) {
    const cartCount = document.getElementById('cart-count');
    const totalCount = products.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalCount;

    // Optionnel : cacher le badge si 0
    cartCount.style.display = totalCount > 0 ? 'flex' : 'none';
}

// Appel dans DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('token');

    if (token) {
        axios.get('/api/cart', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (response.data.success) {
                updateCartCount(response.data.data); // ✅ Appel ici
            } else {
                updateCartCount([]); // Set to 0 if API call fails
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération du compteur du panier:', error);
            updateCartCount([]); // Set to 0 if API call fails
        });
    } else {
        updateCartCount([]); // Set to 0 if user is not logged in
    }
});



</script>
