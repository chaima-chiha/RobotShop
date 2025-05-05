
<script>
document.getElementById('LoginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        email: document.getElementById('loginEmail').value,
        password: document.getElementById('loginPassword').value
    };

    axios.post('/api/login', formData)
        .then(function (response) {
            // Stockage du token reçu après login
            localStorage.setItem('token', response.data.result.token);

            // Affichage d'une alerte de succès
            showModal('✅ Connexion réussie','success');

               // Redirection après 2 secondes
               setTimeout(() => {
                window.location.href = '/';
            }, 1000);
        })
        .catch(function (error) {
            if (error.response && error.response.data && error.response.data.errors) {
                const errors = error.response.data.errors;
                const firstKey = Object.keys(errors)[0];
                const firstErrorMessage = errors[firstKey][0];

                // Affichage de la première erreur via showModal
                showModal(`❌ ${firstErrorMessage}`,'danger');
            } else {
                // Erreur inconnue
                showModal('❌ Une erreur est survenue. Veuillez réessayer.','danger');
            }
        });
});


</script>
