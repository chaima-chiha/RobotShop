@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="app">
    <h1>Mon Compte</h1>
    <div id="user-details">
        <p>Loading user details...</p>
    </div>
    <button id="logout" class="btn btn-secondary">Logout</button>

    @include('client.profile_js')
    <form id="update-user-form">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password">
        <label for="password-confirmation">Confirm New Password:</label>
        <input type="password" id="password-confirmation" name="password_confirmation">
        <button type="button" class="btn btn-secondary" id="update-user-details">Update Details</button>
    </form>
    @include('client.profilUpdate_js')

</div>
@endsection
