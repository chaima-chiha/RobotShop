<script>
    document.addEventListener('DOMContentLoaded', function () {
        const videosContainer = document.getElementById('videos-container');
        const loadingSpinner = document.getElementById('loading');

        function fetchVideos() {
            loadingSpinner.style.display = 'block';

            axios.get('/api/videos')
                .then(response => {
                    if (response.data.success) {
                        displayVideos(response.data.data);
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

        function displayVideos(videos) {
            if (videos.length === 0) {
                videosContainer.innerHTML = '<p>Aucune vidéo trouvée.</p>';
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
                        <a href="/videos/${video.id}" class="btn btn-link text-decoration-none p-0">
                            <i class="fas fa-play-circle fa-lg"></i>
                        </a>
                    </div>
                    <p class="card-text text-muted">${video.description}</p>
                </div>

                <div class="mt-3">
                    ${video.level ? `<span class="badge bg-success mb-2">${video.level}</span>` : ''}
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">${video.duration || 'Durée inconnue'}</small>
                        <div class="progress" style="width: 40%; height: 6px;">
                            <div class="progress-bar bg-info" style="width: ${video.progress || 0}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;


            });

            videosContainer.innerHTML = videosHTML;
        }

        fetchVideos();
    });
</script>
