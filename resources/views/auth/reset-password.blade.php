@extends('layouts.app')

@section('title', 'Réinitialisation du mot de passe')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow p-4 rounded-4">
        <h3 class="text-center mb-4">🔐 Réinitialiser le mot de passe</h3>

        <form id="resetForm">
            <input type="hidden" id="token" value="{{ request()->get('token') }}">

            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" required placeholder="exemple@mail.com">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="password" required placeholder="Mot de passe">
            </div>

            <div class="mb-3">
                <label for="reset_password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="reset_password_confirmation" name="password_confirmation" required placeholder="Confirmez le mot de passe">
            </div>

            <button type="submit" class="btn btn-success w-100">Réinitialiser</button>
        </form>
    </div>
</div>

@include('auth.reset_password_js')
@include('partials.loginModal')
@endsection
