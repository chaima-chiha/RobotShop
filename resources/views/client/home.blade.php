@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

<!-- Carrousel Bootstrap -->
<div id="homeCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('img/slide1.jpg') }}" class="d-block w-100" alt="Slide 1">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('img/slide2.jpg') }}" class="d-block w-100" alt="Slide 2">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('img/slide3.jpg') }}" class="d-block w-100" alt="Slide 3">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Section Produits -->
<div class="container pb-5">

    <div class="text-dark mb-4">
        <h1 class="text-center">Nos Formations</h1>
    </div>

    <div id="videos-container" class="row g-4">
        @include('videos.videoAcceuil_js')
    </div>

    <div class="text-dark mb-4">
        <h1 class="text-center">Nos Nouveautés</h1>
    </div>

    <div id="loading" class="text-center mb-4">Téléchargement des produits</div>

    <div id="products-new" class="row g-4 mb-5">
        @include('products.nouveau_js')
    </div>

    <div class="text-dark mb-4">
        <h1 class="text-center">Nos Promotions</h1>
    </div>

    <div id="products-promo" class="row g-4">
        @include('products.promotion_js')
    </div>

</div>

    <!-- Modal Lecture Vidéo -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4">
                <div class="modal-header bg-success text-white">
                    <div>
                        <h5 class="modal-title" id="video-modal-title">Titre de la vidéo</h5>
                        <p id="video-modal-description" class="mb-0 small text-white-50">Description de la vidéo</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"
                        aria-label="Fermer"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9">
                        <video id="video-player" class="w-100 rounded-bottom" controls>
                            <source src="" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


