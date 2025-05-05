<script>
    document.addEventListener('DOMContentLoaded', function () {
        const videosContainer = document.getElementById('videos-container');
        const loadingSpinner = document.getElementById('loading');
        const levelFilter = document.getElementById('level-filter');


        function fetchVideos(niveau = 'all') {
    loadingSpinner.style.display = 'block';

    let url = '/api/videos';
    if (niveau !== 'all') {
        url += `?niveau=${encodeURIComponent(niveau)}`;
    }

    axios.get(url)
        .then(response => {
            if (response.data.success) {
                const videos = response.data.data;
                displayVideos(videos);
            } else {
                videosContainer.innerHTML = '<p>Erreur lors du chargement des vidéos.</p>';
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des vidéos:', error);
            videosContainer.innerHTML = '<p>Erreur lors du chargement des vidéos.</p>';
        })
        .finally(() => {
            loadingSpinner.style.display = 'none';
        });
}

function getLevelBadge(niveau) {
    switch (niveau) {
        case 'Débutant':
            return `<span class="badge bg-success mb-2"><i class="fas fa-seedling me-1"></i>${niveau}</span>`;
        case 'Intermédiaire':
            return `<span class="badge bg-warning text-dark mb-2"><i class="fas fa-leaf me-1"></i>${niveau}</span>`;
        case 'Avancé':
            return `<span class="badge bg-danger mb-2"><i class="fas fa-fire me-1"></i>${niveau}</span>`;
        default:
            return '';
    }
}

function displayVideos(videos) {
    if (videos.length === 0) {
        videosContainer.innerHTML = '<p>Aucune vidéo trouvée.</p>';
        return;
    }

    let videosHTML = '';
videos.forEach(video => {
    let filesHtml = '';
/*
    if (video.files && video.files.length > 0) {
        filesHtml += `
        <div class="video-files">
            <h6>Fichiers associés :</h6>
            <ul>
            `;

        video.files.forEach(file => {
            filesHtml += `
                <li>
                    <a href=${file.file_path ? '/storage/'+file.file_path:'/images/default-file_path.jpg'} target="_blank">

                        <i class="fas fa-file me-1"></i>${file.name}
                    </a>
                </li>
            `;
        });

        filesHtml += `
            </ul>
        </div>
        `;
    }
*/

// Ajouter bouton d'achat si niveau avancé
let buyButtonHtml = '';

if (video.niveau === 'Avancé') {
    buyButtonHtml = `
        <button class="btn btn-danger btn-sm mt-2 w-100 add-to-cart-btnvideo"
              data-video-id="${video.id}"
              data-product-name="${video.title}"
              data-product-price="${video.price}">
            Acheter - ${video.price ? video.price.toFixed(2) + ' TND' : 'Prix non défini'}
        </button>
    `;
}

videosHTML += `
    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
        <div class="card video-card shadow-sm h-100">
            <img src="${video.thumbnail ? '/storage/' + video.thumbnail : '/images/default-thumbnail.png'}"
                class="card-img-top" alt="${video.title}">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title fw-bold">${video.title}</h5>
                        <a href="#"
                            class="btn btn-link text-decoration-none p-0 play-video-btn"
                            data-video-id="${video.id}"
                            data-video-url="${video.video_path ? '/storage/' + video.video_path : '/images/default-video_path.mp4'}"
                            data-title="${video.title}"
                            data-description="${video.description}"
                               data-niveau="${video.niveau}">play
                            <i class="fas fa-play-circle fa-lg"></i>
                        </a>
                    </div>
                    <p class="card-text text-muted">${video.description}</p>
                    ${filesHtml}
                </div>
                <div class="mt-3">
                    ${video.niveau ? getLevelBadge(video.niveau) : ''}
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">${Math.ceil(video.duration / 60)} min</small>
                    </div>
                </div>
                <a href="/videos/details/${video.id}" class="btn btn-outline-primary btn-sm mt-2">
                    Produits & Codes
                </a>
                ${buyButtonHtml}
            </div>
        </div>
    </div>
        `;
    });

    videosContainer.innerHTML = videosHTML;
}


        // Changement du filtre
        if (levelFilter) {
            levelFilter.addEventListener('change', function () {
                fetchVideos(this.value);
            });
        }


        //*************


//Vidéo ajoutée au panier
document.addEventListener('click', function (e) {
    const buyBtn = e.target.closest('.add-to-cart-btnvideo');

    if (buyBtn) {
        e.preventDefault();
        const videoId = buyBtn.dataset.videoId;

        const token = localStorage.getItem('token');
        if (!token) {
            showModal('Veuillez vous connecter pour ajouter au panier.');
            return;
        }

        axios.post('/api/cart/add-video', {
            video_id: videoId,
            quantity: 1
        }, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })
        .then(response => {

            if (response.data.success) {
                showModal('Vidéo ajoutée au panier.');
            } else {
                showModal('Erreur lors de l’ajout au panier.');
            }
        })
        .catch(error => {
            console.error('Erreur ajout au panier:', error);
            showModal('Erreur lors de l’ajout au panier.');
        });
    }
});



       // Gestion du clic sur lecture vidéo
document.addEventListener('click', function (e) {
    const playBtn = e.target.closest('.play-video-btn');
    if (playBtn) {
        e.preventDefault();

        const token = localStorage.getItem('token');
        if (!token) {
            showModal('Veuillez vous connecter pour regarder la vidéo.');
            return;
        }

        const videoId = playBtn.dataset.videoId;
        const niveau = playBtn.dataset.niveau;

        const playVideo = () => {
            const videoUrl = playBtn.dataset.videoUrl;
            const title = playBtn.dataset.title;
            const description = playBtn.dataset.description;

            document.getElementById('video-player').src = videoUrl;
            document.getElementById('video-modal-title').textContent = title;
            document.getElementById('video-modal-description').textContent = description;

            const modal = new bootstrap.Modal(document.getElementById('videoModal'));
            modal.show();

            axios.post(`/api/videos/${videoId}/view`, {}, {
                headers: { Authorization: `Bearer ${token}` }
            }).then(() => {
                console.log('Visionnage enregistré');
            }).catch(error => {
                console.error('Erreur en enregistrant le visionnage:', error);
            });
        };

        if (niveau === 'Avancé') {
            const code = prompt("Entrez le code d'activation de cette vidéo avancée :");
            if (!code) return;

            axios.post(`/api/videos/${videoId}/verify-code`, {
                code: code
            }, {
                headers: { Authorization: `Bearer ${token}` }
            })
            .then(res => {
                if (res.data.valid) {
                    playVideo();
                } else {
                    alert('Code invalide ou expiré.');
                }
            })
            .catch(() => {
                alert('Erreur lors de la vérification du code.');
            });
        } else {
            playVideo();
        }
    }
});


        // Nettoyage du player à la fermeture du modal
        document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
            const player = document.getElementById('video-player');
            player.pause();
            player.currentTime = 0;
            player.src = '';
        });

        // Chargement initial
        fetchVideos();
    });
</script>
