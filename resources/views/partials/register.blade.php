
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
            console.log(response.data)
            // Stockage du token si nécessaire
            localStorage.setItem('token', response.data.result.token);

            // Redirection vers la liste des produits
            window.location.href = '/profil';
        })
        .catch(function (error) {
            // Gestion des erreurs
            if (error.response) {
                const errors = error.response.data.errors;
                // Afficher les erreurs à l'utilisateur
                Object.keys(errors).forEach(key => {
                    // Ajoutez ici votre logique pour afficher les erreurs
                    console.log(errors[key][0]);
                });
            }
        });
});
</script>
