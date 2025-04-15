@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div>
    <h1>Mon Compte</h1>


    <div id="user-details" class="mb-4">
        <p><strong>Email :</strong> <span id="user-email">...</span></p>
        <p><strong>Nom :</strong> <span id="user-name">...</span>
        <p><strong>Adresse :</strong> <span id="user-address">...</span></p>
        <p><strong>Téléphone :</strong> <span id="user-phone">...</span></p>
        <p><strong> vous pouvez modifier vos info</strong>
            <button class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#editNameModal">
                <i class="fas fa-pen"></i>
            </button>
        </p>
    </div>

 <!-- ✅ Modal pour modifier le nom et l'adresse -->
<div class="modal fade" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="editNameModalLabel">Modifier les informations</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form id="editNameForm">
                    <div class="mb-3">
                        <label for="new-name" class="form-label">Nouveau nom</label>
                        <input type="text" class="form-control" id="new-name" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="new-address" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="new-address" name="adresse" required>
                    </div>
                    <div class="mb-3">
                        <label for="new-phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="new-phone" name="telephone" required>
                    </div>

                    <div id="modalAlertPlaceholder"></div>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@include('client.profile_js')
@endsection


