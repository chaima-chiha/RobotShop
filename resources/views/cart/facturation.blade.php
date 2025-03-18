
@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Payment</h1>
    <form id="payment-form">
        <div class="form-group">
            <label for="card-number">Card Number</label>
            <input type="text" class="form-control" id="card-number" placeholder="Enter card number">
        </div>
        <div class="form-group">
            <label for="card-holder">Card Holder Name</label>
            <input type="text" class="form-control" id="card-holder" placeholder="Enter card holder name">
        </div>
        <div class="form-group">
            <label for="expiry-date">Expiry Date</label>
            <input type="text" class="form-control" id="expiry-date" placeholder="MM/YY">
        </div>
        <div class="form-group">
            <label for="cvv">CVV</label>
            <input type="text" class="form-control" id="cvv" placeholder="Enter CVV">
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Pay Now</button>
    </form>
</div>


<script src="{{ asset('js/payment.js') }}"></script>


@endsection
