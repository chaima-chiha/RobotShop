@extends('layouts.app')

@section('title', 'Accueil')

@section('content')


   <div class="container-fluid contact py-5">
    <div class="container py-5">
        <div class="p-5 bg-light rounded">
            <div class="row g-4">
                <div class="col-12">
                    <div class="text-center mx-auto" style="max-width: 700px;">
                        <h1 class="text-primary">Nous Contactez</h1>
                        <p class="mb-4">Notre meilleur site d'apprentissage et d'achat de robotique.</p>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="col-lg-7">
                    <form method="POST" action="{{ route('contact.send') }}">
    @csrf
    <input type="text" name="name" class="w-100 form-control border-0 py-3 mb-4" placeholder="Votre Nom" required>
    <input type="email" name="email" class="w-100 form-control border-0 py-3 mb-4" placeholder="Votre Email" required>
    <textarea name="message" class="w-100 form-control border-0 mb-4" rows="5" cols="10" placeholder="Votre Message" required></textarea>
    <button class="w-100 btn form-control border-secondary py-3 bg-white text-primary" type="submit">Envoyer</button>
</form>
                </div>
                <div class="col-lg-5">
                    <div class="d-flex p-4 rounded mb-4 bg-white">
                        <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                        <div>
                            <h4>Address</h4>
                            <p class="mb-2">19 Rue El Jahedh Nabeul</p>
                        </div>
                    </div>
                    <div class="d-flex p-4 rounded mb-4 bg-white">
                        <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                        <div>
                            <h4>Mail Us</h4>
                            <p class="mb-2">RobotShopAcademy@gmail.com</p>
                        </div>
                    </div>
                    <div class="d-flex p-4 rounded bg-white">
                        <i class="fa fa-phone-alt fa-2x text-primary me-4"></i>
                        <div>
                            <h4>Telephone</h4>
                            <p class="mb-2">(+215) 99 847 516</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
