@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <div class="container text-center">
        <h1 class="text-primary">Bienvenue sur votre profil</h1>
        <p class="text-muted">en attente de liste de produit a realiser</p>

    </div>
      <!-- teacher -->
      <div class="col-lg-4 col-sm-6 mb-5 mb-lg-0">
        <div class="card border-0 rounded-0 hover-shadow">
          <img class="card-img-top rounded-0" src="template/images/teachers/teacher-1.jpg" alt="teacher">
          <div class="card-body">
            <a href="teacher-single.html">
              <h4 class="card-title">nom</h4>
            </a>
            <p>client</p>
            <ul class="list-inline">
              <li class="list-inline-item"><a class="text-color" href="#"><i class="ti-facebook"></i></a></li>
              <li class="list-inline-item"><a class="text-color" href="#"><i class="ti-twitter-alt"></i></a></li>
              <li class="list-inline-item"><a class="text-color" href="#"><i class="ti-google"></i></a></li>
              <li class="list-inline-item"><a class="text-color" href="#"><i class="ti-linkedin"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
@endsection
