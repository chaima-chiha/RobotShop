@extends('layouts.app')

@section('content')

<div>

</div>
    <div id="loading" style="text-align: center;">téléchargement des produits </div>

        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <div class="tab-class text-center">
                    <div class="row g-4">
                        <div class="col-lg-6 text-start">
                            <h1>Liste des Produits</h1>
                        </div>
                        <div class="col-lg-6 text-end">
                            <ul class="nav nav-pills d-inline-flex text-center mb-5">
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                        <span class="text-dark" style="width: 130px;">Nos produits</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="d-flex py-2 m-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-2" id="new-products-tab">
                                        <span class="text-dark" style="width: 130px;">Nouveau</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-3">
                                        <span class="text-dark" style="width: 130px;">Promotion</span>
                                    </a>
                                </li>
                            </ul>
                        </div>



                        <!--<div class="col-lg-6 text-end">
                            <ul class="nav nav-pills d-inline-flex text-center mb-5">
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                        <span class="text-dark" style="width: 130px;">Nos produits</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="d-flex py-2 m-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-2">
                                        <span class="text-dark" style="width: 130px;">Nouveau</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-3">
                                        <span class="text-dark" style="width: 130px;">Promotion</span>
                                    </a>
                                </li>
                            </ul>
                        </div>-->

                    </div>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div  id="products" class="row g-4">
                                       @include('products.index_js')
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-2" class="tab-pane fade show p-0 ">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <div  id="products-new" class="row g-4">
                                            @include('products.nouveau_js')
                                         </div>
                                    </div>
                                </div>
                        </div>

                            <div id="tab-3" class="tab-pane fade p-0">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <div id="products-promo-container" class="row g-4">
                                            @include('products.promotion_js')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        

                    </div>
                </div>
            </div>
        </div>
@endsection








