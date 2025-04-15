<script>


window.showModal = function (message, type = 'info') {
    const modalElement = document.getElementById('alertModal');
    const modalBody = document.getElementById('alertModalMessage');
    const modalHeader = modalElement.querySelector('.modal-header');
    const modalTitle = modalElement.querySelector('.modal-title');

    const headerClasses = {
        success: 'bg-success text-white',
        danger: 'bg-danger text-white',
        warning: 'bg-warning text-dark',
        info: 'bg-info text-white'
    };

    // Reset header classes
    modalHeader.className = 'modal-header';
    modalHeader.classList.add(...(headerClasses[type] || headerClasses['info']).split(' '));

    // Set title based on type
    modalTitle.textContent = {
        success: 'Succès',
        danger: 'Erreur',
        warning: 'Attention',
        info: 'Information'
    }[type] || 'Alerte';

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
    axios.get('/api/cart', {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    })
    .then(response => {
        if (response.data.success) {
            updateCartCount(response.data.data); // ✅ Appel ici
        }
    })
    .catch(error => {
        console.error('Erreur lors de la récupération du compteur du panier:', error);
    });
});


</script>
