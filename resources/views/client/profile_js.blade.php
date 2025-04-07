<script>
        document.addEventListener('DOMContentLoaded', function() {
              const token = localStorage.getItem('token');
            axios.get('/api/profile',{
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('token')}`
                        }
                    })
                .then(response => {
                    const userDetails = response.data;
                    const userDetailsDiv = document.getElementById('user-details');

                    userDetailsDiv.innerHTML = `
                        <p><strong>Name:</strong> ${userDetails.name}</p>
                        <p><strong>Email:</strong> ${userDetails.email}</p>
                    `;
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des détails de l\'utilisateur:', error);
                    const userDetailsDiv = document.getElementById('user-details');
                    userDetailsDiv.innerHTML = '<p>Failed to load user details.</p>';
                });







        });
</script>
