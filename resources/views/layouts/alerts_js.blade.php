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


</script>
