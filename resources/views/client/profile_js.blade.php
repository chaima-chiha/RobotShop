<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('token');

        // 🔁 Charger les infos utilisateur
        axios.get('/api/profile', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        }).then(response => {
            const user = response.data;
            document.getElementById('user-name').textContent = user.name;
            document.getElementById('user-email').textContent = user.email;
            document.getElementById('user-address').textContent = user.adresse ?? '';
            document.getElementById('new-name').value = user.name;
            document.getElementById('new-address').value = user.adresse ?? '';
            document.getElementById('user-phone').textContent = user.telephone ?? '';
            document.getElementById('new-phone').value = user.telephone ?? '';

        }).catch(error => {
            console.error('Erreur chargement utilisateur', error);
        });

        // ✏️ Soumettre la modification du nom + adresse
        document.getElementById('editNameForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const newName = document.getElementById('new-name').value;
            const newAddress = document.getElementById('new-address').value;
            const newPhone = document.getElementById('new-phone').value;

            axios.put('/api/update-profile', {
                nom: newName,
                adresse: newAddress,
                telephone: newPhone
            }, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })
            .then(res => {
                const user = res.data.user;
                document.getElementById('user-name').textContent = user.name;
                document.getElementById('user-address').textContent = user.adresse;
                document.getElementById('user-phone').textContent = user.telephone;

                showAlert('success', 'Profil mis à jour avec succès.');
                const modal = bootstrap.Modal.getInstance(document.getElementById('editNameModal'));

            })
            .catch(err => {
                console.error(err);
                showAlert('danger', 'Erreur lors de la mise à jour.');
            });
        });

        // 🔔 Affichage d’alertes Bootstrap dans le modal
        function showAlert(type, message) {
            const placeholder = document.getElementById('modalAlertPlaceholder');
            placeholder.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            `;
        }
    });
    </script>
