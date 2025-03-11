@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="app">
    <h1>User Profile</h1>
    <div id="user-details">
        <p>Loading user details...</p>
    </div>
    <button id="logout">Logout</button>
    @include('client.profile_js')

</div>
@endsection
