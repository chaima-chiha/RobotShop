
<script>
    const form = document.getElementById('resetForm');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const data = {
            token: document.getElementById('token').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('passwordconfirmation').value,
        };

        try {
            const response = await axios.post('/api/reset-password', data);
            document.getElementById('message').innerText = response.data.message;
        } catch (error) {
            if (error.response) {
                document.getElementById('message').innerText = error.response.data.message || 'Erreur';
            }
        }
    });
</script>
