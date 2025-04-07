
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const videoContainer = document.getElementById('video-container');
        const loadingSpinner = document.getElementById('loading');
        const videoId = window.location.pathname.split('/').pop();


        function fetchVideo(id) {
            loadingSpinner.style.display = 'block';

            axios.get(`/api/videos/${videoId}`)
                .then(response => {
                    if (response.data.success) {
                        displayVideo(response.data.data);

                    } else {
                        videoContainer.innerHTML = '<p>Erreur lors du chargement de la vidéo.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération de la vidéo:', error);
                    videoContainer.innerHTML = '<p>Erreur lors du chargement de la vidéo.</p>';
                })
                .finally(() => {
                    loadingSpinner.style.display = 'none';
                });
        }

        function displayVideo(video) {
            const videoHTML = `

                <div >
                    <video
                        id="video_player"
                        class="video-js vjs-default-skin vjs-big-play-centered"
                        controls
                        preload="auto"
                        width="100%"
                        data-setup="{}">
                        <source src="${video.video_path ? '/storage/' + video.video_path : '/images/default-video_path.mp4'}" type="video/mp4">
                        <p class="vjs-no-js">
                            To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video.
                        </p>
                    </video>
                </div>
                <h1 class="mt-3">${video.title}</h1>
                <p>${video.description}</p>
            `;

            videoContainer.innerHTML = videoHTML;
        }

        fetchVideo(videoId);
    });
</script>
