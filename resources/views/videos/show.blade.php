@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div id="loading" style="text-align: center;">Chargement de la vidéo...</div>
    <div id="video-container"></div>
</div>

@include('videos.show_js')
@endsection
