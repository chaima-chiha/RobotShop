@extends('layouts.app')

@section('content')

<div class="container py-5">
    <h1 class="text-center">Liste des Vidéos</h1>
    <div id="loading" style="text-align: center;">Chargement des vidéos...</div>
    <div id="videos-container" class="row g-4"></div>
</div>


    @include('videos.index_js')
@endsection
