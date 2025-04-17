@extends('layouts.app')

@section('content')

<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 text-md-start text-center mb-3 mb-md-0">
            <h1 class="fw-bold">Nos Vidéos</h1>
        </div>
        <div class="col-md-6 text-md-end text-center">
            <label for="level-filter" class="form-label me-2">Filtrer par niveau :</label>
            <select id="level-filter" class="form-select d-inline-block w-auto">
                <option value="all">Tous les niveaux</option>
                <option value="Débutant">Débutant</option>
                <option value="Intermédiaire">Intermédiaire</option>
                <option value="Avancé">Avancé</option>
            </select>
        </div>
    </div>

    <div id="loading" class="text-center mb-4" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>

    <div id="videos-container" class="row g-4"></div>
</div>



    @include('videos.index_js')


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
