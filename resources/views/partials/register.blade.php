<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        name: document.getElementById('signupName').value,
        email: document.getElementById('signupEmail').value,
        password: document.getElementById('signupPassword').value,
        password_confirmation: document.getElementById('password_confirmation').value
    };

    axios.post('/api/signup', formData)
        .then(function (response) {
            localStorage.setItem('token', response.data.result.token);

            // Utilisation de la modale globale pour le message de succès
            showModal('✅ Inscription réussie ! Redirection en cours...','success');

            // Redirection après 2 secondes
            setTimeout(() => {
                window.location.href = '/profil';
            }, 1000);
        })
        .catch(function (error) {
            if (error.response && error.response.data && error.response.data.errors) {
                const errors = error.response.data.errors;
                const firstErrorKey = Object.keys(errors)[0];
                const firstErrorMessage = errors[firstErrorKey][0];

                // Utilisation de showModal pour afficher la première erreur
                showModal(`❌ ${firstErrorMessage}`,'danger');
            } else {
                showModal('❌ Une erreur est survenue. Veuillez réessayer plus tard.','danger');
            }
        });
});
</script>
