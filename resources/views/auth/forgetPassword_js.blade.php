<script>
    const sendResetLink = async (email) => {
        try {
            const response = await axios.post('/api/forgot-password', { email });

            // Utilisation de showModal pour afficher le message de succès
            showModal(`✅ ${response.data.message}`);
        } catch (error) {
            if (error.response && error.response.data && error.response.data.message) {
                showModal(`❌ ${error.response.data.message}`);
            } else {
                showModal('❌ Une erreur est survenue. Veuillez réessayer plus tard.');
            }
            console.error('Error sending reset link:', error);
        }
    };

    document.getElementById('reset-link-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const email = this.elements.email.value;
        sendResetLink(email);
    });
    </script>
