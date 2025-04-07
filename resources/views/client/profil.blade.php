@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="app">
    <h1>Mon Compte</h1>
    <div id="user-details">
        <p>Loading user details...</p>
    </div>



    @include('client.profile_js')
</div>
@endsection


