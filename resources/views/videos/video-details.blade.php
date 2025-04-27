@extends('layouts.app')

@section('content')

<div class="container py-4">
    <h2 id="video-title"></h2>
    <p id="video-description"></p>
    <p><strong>Durée :</strong> <span id="video-duration"></span>min</p>
<p><strong>Niveau :</strong> <span id="video-niveau"></span></p>
<div id="video-progress" class="mb-3"></div>
<h6>Fichiers associés :</h6>
<div id="video-files" class="mb-4"></div>

    <h4>Produits associés</h4>
    <div id="products" class="row"></div>
    <div id="loading" style="display: none;">Chargement...</div>

    <h4>Vidéos similaires</h4>
<div id="similar-videos" class="row mb-5"></div>


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
    const videoId = window.location.pathname.split('/').pop();
    axios.get(`/api/videos/${videoId}/with-products`)
    .then(response => {
    const video = response.data.data;
    const similarVideos = response.data.similar_videos;
displaySimilarVideos(similarVideos);

    document.getElementById('video-title').textContent = video.title;
    document.getElementById('video-description').textContent = video.description;
    document.getElementById('video-duration').textContent = Math.ceil(video.duration / 60);
    document.getElementById('video-niveau').innerHTML = getLevelBadge(video.niveau);

   if (video.files && video.files.length > 0) {
        document.getElementById('video-files').innerHTML = `
            <ul>
                ${video.files.map(file => `
                    <li><a href="${file.file_path ? '/storage/' + file.file_path : '/images/default-file_path.jpg'}" target="_blank">
                        <i class="fas fa-file"></i> ${file.name}
                    </a></li>
                `).join('')}
            </ul>
        `;

    } else {
        document.getElementById('video-files').innerHTML = '<p>Aucun fichier associé.</p>';
    }

    displayProducts(video.products);
})

                    .catch(error => {
                        console.error(error);
                    });
            });



            function getLevelBadge(niveau) {
    switch (niveau) {
        case 'Débutant':
            return `<span class="badge bg-success"><i class="fas fa-seedling me-1"></i>${niveau}</span>`;
        case 'Intermédiaire':
            return `<span class="badge bg-warning text-dark"><i class="fas fa-leaf me-1"></i>${niveau}</span>`;
        case 'Avancé':
            return `<span class="badge bg-danger"><i class="fas fa-fire me-1"></i>${niveau}</span>`;
        default:
            return '';
    }
}

function displayProducts(products) {
    const productsContainer = document.getElementById('products');

    if (products.length === 0) {
        productsContainer.innerHTML = '<p>Aucun produit associé.</p>';
        return;
    }

    let productsHTML = '';
    products.forEach(product => {
        const isPromoted = product.is_promoted;
        const isNew=product.is_new;
        const originalPrice = `${product.price} dt`;
        const discountedPrice = (product.price - (product.price * product.promotion / 100)).toFixed(2) + ' dt';

        productsHTML += `
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <div class="card shadow-sm h-100 rounded product-card position-relative">
                    <img src="${product.image ? '/storage/' + product.image : '/images/default.png'}"
                        class="card-img-top rounded-top" alt="${product.name}" loading="lazy">
                    <div>
                        ${isPromoted ? `<span class="badge badge-promo bg-warning text-dark">Promo-${product.promotion}%</span>` : ''}
                        ${isNew ? `<span class="badge badge-new bg-primary text-dark">Nouveau</span>` : ''}
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold">${product.name}</h5>
                        </div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="card-text text-muted mb-2">
                                ${isPromoted
                                    ? `<span class="text-muted text-decoration-line-through">${originalPrice}</span><br>
                                       <span class="text-success fw-bold">${discountedPrice}</span>`
                                    : `<span class="text-success fw-bold">${originalPrice}</span>`
                                }
                            </div>
                            <div>${product.available_stock}</div>
                            <div>
                                ${product.available_stock > 0
                                    ? `<span class="m-2">En stock</span>`
                                    : `<span class="m-2">Épuisé</span>`}
                            </div>
                        </div>
                        <div class="justify-content-between align-items-center mt-auto">
                            <div class="buttons">
                                <a href="/products/${product.id}" class="btn btn-link p-0">
                                    <i class="fas fa-eye fa-lg text-primary"></i> Voir
                                </a>
                                <button
                                    class="btn btn-buy rounded-pill add-to-cart-btn"
                                    data-product-id="${product.id}"
                                    data-product-stock="${product.available_stock}">
                                    <i class="fa fa-shopping-bag"></i> Acheter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    productsContainer.innerHTML = productsHTML;

    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = button.getAttribute('data-product-id');
            const stock = parseInt(button.getAttribute('data-product-stock'));

            if (stock <= 0) {
                showModal('Produit insuffisant ou épuisé.', 'warning');
                return;
            }

            addToCart(productId);
        });
    });
}

function addToCart(productId) {
    const token = localStorage.getItem('token');

    if (!token) {
        showModal('Vous devez être connecté pour ajouter au panier.', 'warning');
        return;
    }

    axios.post('/api/cart', {
        product_id: productId,
        quantity: 1
    }, {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
    .then(response => {
        if (response.data.success) {
            showModal('Produit ajouté au panier avec succès !', 'success');
        } else {
            showModal('Erreur lors de l\'ajout au panier.', 'danger');
        }
    })
    .catch(error => {
        showModal('Erreur lors de l\'ajout au panier.', 'danger');
    });
}


function displaySimilarVideos(videos) {
    const container = document.getElementById('similar-videos');

    if (videos.length === 0) {
        container.innerHTML = '<p>Aucune vidéo similaire trouvée.</p>';
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
                                    data-description="${video.description}">
                                    <i class="fas fa-play-circle fa-lg"></i>play
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

    container.innerHTML = videosHTML;
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

                axios.post(`/api/videos/${playBtn.dataset.videoId}/view`, {}, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}` // si tu utilises Sanctum + SPA
                    }
                })
                .then(response => {
                    console.log('Visionnage enregistré');
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

</script>
@endsection

