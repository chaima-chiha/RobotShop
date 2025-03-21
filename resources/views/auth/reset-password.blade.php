
@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <h2>Reset Your Password</h2>
    <form id="resetForm">

        <input type="hidden" id="token" value="{{ request()->get('token') }}">
        <input type="email" id="email" placeholder="Email" required><br><br>
        <input type="password" id="password" placeholder="New Password" required><br><br>
        <input type="password" id="passwordconfirmation" placeholder="Confirm Password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>

    <div id="message"></div>

   @include('auth.reset_password_js')




   @endsection
