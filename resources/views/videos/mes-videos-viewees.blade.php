@extends('layouts.app')

@section('content')
<div class="container my-5">

    <h1 class="mb-4">Mes Vidéos Regardées</h1>

    <div id="loading" class="text-center" style="display:none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>

    <div id="videos-container" class="row">
        <!-- Les cartes vidéos seront injectées ici -->
    </div>

    <!-- Modal pour lire une vidéo -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="video-modal-title">Lecture de la vidéo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <video id="video-player" class="w-100" controls>
              <source src="" type="video/mp4">
              Votre navigateur ne supporte pas la lecture vidéo.
            </video>
            <p id="video-modal-description" class="mt-2"></p>
          </div>
        </div>
      </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const videosContainer = document.getElementById('videos-container');
        const loadingSpinner = document.getElementById('loading');

        function fetchViewedVideos() {
            loadingSpinner.style.display = 'block';

            axios.get('/api/user/video-history', {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}` // Auth avec token Sanctum
                }
            })
            .then(response => {
                if (response.data.success) {
                    const videos = response.data.data;
                    displayVideos(videos);
                } else {
                    videosContainer.innerHTML = '<p>Erreur lors du chargement des vidéos regardées.</p>';
                }
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des vidéos regardées:', error);
                videosContainer.innerHTML = '<p>Erreur lors du chargement des vidéos regardées.</p>';
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
                videosContainer.innerHTML = '<p>Aucune vidéo regardée pour le moment.</p>';
                return;
            }

            let videosHTML = '';
            videos.forEach(video => {
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
                                            data-description="${video.description}">play
                                            <i class="fas fa-play-circle fa-lg"></i>
                                        </a>
                                    </div>
                                    <p class="card-text text-muted">${video.description}</p>
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

        // Lecture vidéo
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

                // (Optionnel) Réenregistrer la vue quand il relance ?
                axios.post(`/api/videos/${playBtn.dataset.videoId}/view`, {}, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
                })
                .then(response => {
                    console.log('Visionnage enregistré.');
                })
                .catch(error => {
                    console.error('Erreur en enregistrant le visionnage:', error);
                });
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
        fetchViewedVideos();
    });
</script>
@endsection
