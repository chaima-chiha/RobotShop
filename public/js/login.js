document.getElementById('LoginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        email: document.getElementById('loginEmail').value,
        password: document.getElementById('loginPassword').value
    };

    axios.post('/api/login', formData)
        .then(function (response) {

            // Stockage du token reçu après login
            localStorage.setItem('token', response.data.token);
             // Vérification du stockage du token
             console.log(' enregistré :', response.data.name);

            // Redirection après connexion réussie
            window.location.href = '/pofil';
        })
        .catch(function (error) {
            // Gestion des erreurs
            if (error.response) {
                const errors = error.response.data.errors;
                Object.keys(errors).forEach(key => {
                    console.log(errors[key][0]); // Ajoute ici ton affichage des erreurs
                });
            } else {
                console.error("Erreur de connexion :", error);
            }
        });
});
