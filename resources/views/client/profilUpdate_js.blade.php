<script>
document.addEventListener('DOMContentLoaded', function () {
    const userDetailsDiv = document.getElementById('user-details');
    const updateUserForm = document.getElementById('update-user-details');

    function fetchUserDetails() {
        axios.get('/api/user-details', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(response => {
            const user = response.data.user;
            userDetailsDiv.innerHTML = `
                <p><strong>Name:</strong> ${user.name}</p>
                <p><strong>Email:</strong> ${user.email}</p>
            `;
            // Pré-remplir le formulaire avec les données de l'utilisateur
            document.getElementById('email').value = user.email;
        })
        .catch(error => {
            console.error('Error fetching user details:', error);
            userDetailsDiv.innerHTML = '<p>Failed to load user details.</p>';
        });
    }

    updateUserForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password-confirmation').value;

        const data = {
            email: email,
            password: password,
            password_confirmation: passwordConfirmation
        };

        axios.post('/api/update-user-details', data, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(response => {
            alert(response.data.msg);
            fetchUserDetails(); // Recharger les détails de l'utilisateur
        })
        .catch(error => {
            console.error('Error updating user details:', error);
            alert('Failed to update user details.');
        });
    });

    fetchUserDetails();
});
</script>
