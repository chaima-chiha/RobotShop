@extends('layouts.app')

@section('title', 'Confirmation de Commande')

@section('content')
<div class="container py-5">
    <div class="alert alert-success text-center">
        <h4 class="mb-0">Finaliser Votre Commande !</h4>
    </div>

    <div class="row mt-5 g-4">

        <!-- Colonne Gauche : Formulaire -->
        <div class="col-lg-6">
            <form id="order-form" class="shadow-sm p-4 rounded bg-light">
                <h5 class="mb-3">Informations de livraison</h5>

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom complet</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" required>
                </div>

                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control" id="telephone" name="telephone" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Méthode de livraison</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="livraison" id="livraison-domicile" value="domicile">
                        <label class="form-check-label" for="livraison-domicile">À domicile (+7 TND)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="livraison" id="livraison-retrait" value="retrait" checked>
                        <label class="form-check-label" for="livraison-retrait">Retrait en magasin (gratuit)</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mt-3">📦 Confirmer la commande</button>
            </form>
        </div>

        <!-- Colonne Droite : Récapitulatif -->
        <div class="col-lg-6">
            <div id="order-details" class="shadow-sm p-4 rounded bg-white">
                <h5 class="mb-3">Résumé de votre commande</h5>
                <div id="order-summary-content"></div>
                <p id="order-total" class="fw-bold mt-3"></p>
                <p id="order-total-livraison" class="text-muted d-none">Total avec livraison : <span id="total-livraison-val"></span> TND</p>
            </div>
        </div>
    </div>
</div>


@include('cart.order-confirmation_js')

@endsection
