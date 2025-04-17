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
                                    data-video-url="${video.video_path ? '/storage/' + video.video_path : '/images/default-video_path.mp4'}"
                                    data-title="${video.title}"
                                    data-description="${video.description}">play
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

        // Gestion du clic sur lecture vidéo
        document.addEventListener('click', function (e) {
            const playBtn = e.target.closest('.play-video-btn');
            if (playBtn) {
                e.preventDefault();

                const videoUrl = playBtn.dataset.videoUrl;
                const title = playBtn.dataset.title;
                const description = playBtn.dataset.description;

                document.getElementById('video-player').src = videoUrl;
                document.getElementById('video-modal-title').textContent = title;
                document.getElementById('video-modal-description').textContent = description;

                const modal = new bootstrap.Modal(document.getElementById('videoModal'));
                modal.show();
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
