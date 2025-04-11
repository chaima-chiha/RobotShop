<script>
    const form = document.getElementById('resetForm');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const data = {
            token: document.getElementById('token').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('reset_password_confirmation').value,
        };

        try {
            const response = await axios.post('/api/reset-password', data);

            // Affichage avec showModal
            showModal(`✅ ${response.data.message}`,'success');

         // ouvrir le modal de connexion
         const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();

        } catch (error) {
            if (error.response && error.response.data) {
                const msg = error.response.data.message || '❌ Une erreur est survenue.';
                showModal(`❌ ${msg}`,'danger');
            } else {
                showModal('❌ Une erreur inattendue s’est produite.','danger');
            }
        }
    });
</script>
