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



<!-- Modal d'activation -->
<div class="modal fade" id="codeModal" tabindex="-1" aria-labelledby="codeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header bg-success-subtle text-dark border-bottom border-success">

        <h5 class="modal-title" id="codeModalLabel">Code d’activation requis</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <p>Veuillez entrer le code d’activation de cette vidéo avancée :</p>
        <input type="text" id="activationCodeInput" class="form-control" placeholder="Votre code ici..." />
        <div class="invalid-feedback mt-2 d-none" id="codeError">Le code est requis !</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="validateCodeBtn">Valider</button>
      </div>
    </div>
  </div>
</div>


@endsection
