<script>
    document.addEventListener("DOMContentLoaded", function () {
        axios.get('/api/cart', {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token') // adapte si tu utilises un token
            }
        })
        .then(function (response) {
            if (response.data.success) {
                const count = response.data.data.length;
                document.getElementById('cart-count').textContent = count;
            }
        })
        .catch(function (error) {
            console.error("Erreur lors du chargement du panier", error);
        });
    });
    </script>
