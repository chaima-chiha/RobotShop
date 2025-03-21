<script>

const sendResetLink = async (email) => {
            try {
                const response = await axios.post('/api/forgot-password', { email });
                alert(response.data.message);
            } catch (error) {
                console.error('Error sending reset link:', error);
            }
        };


    document.getElementById('reset-link-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const email = this.elements.email.value;
        sendResetLink(email);
    });
</script>
