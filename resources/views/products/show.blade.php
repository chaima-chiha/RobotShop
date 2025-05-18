@extends('layouts.app')

@section('content')
<div class="container">

    <div id="alert-containerShow" class="mt-3"></div>

    <div id="product-details"></div>

    <h3 class="mt-5">Produits similaires</h3>
<div class="row" id="similar-products"></div>


</div>

@include('products.show_js_')


@endsection
