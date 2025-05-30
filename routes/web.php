<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminInvoiceController;
use App\Http\Controllers\ContactController;

//page d acceuil
Route::get('/', function () { return view('client.home'); });
//page de contact
Route::get('/contact', function () { return view('partials.contact'); });
//mon compte
Route::get('/profil', function () {return view('client.profil');});
//liste de produits
Route::get('/products', function () {return view('products.index');});
//detaille de produits
Route::get('/products/{id}', function () {return view('products.show');});
//afficher les produits d'une catégorie
Route::get('/categories/{id}/products', function ($id) { return view('products.categories_show', ['id' => $id]);});
//afficher le panier
Route::get('/cart',function(){return view('cart.cart');});
//route de reset password
Route::get('/reset-password', function () {return view('auth.reset-password');});
//apercu commande apres valdation
Route::get('/invoice', function () { return view('orders.invoice');});
Route::get('/invoice/{order_id}', function () { return view('orders.invoice');});
//confirmer la commande de client
Route::get('/order-confirmation', function () { return view('cart.order-confirmation');});
//lister commandes de client
Route::get('/mes-commandes', function() { return view('orders.myOrders');});
//liste de videos
Route::get('/videos', function(){ return view('videos.index');});
//regarder video
Route::get('/videos/{id}',function(){ return view('videos.show');});

//pdf
Route::get('/admin/invoice/pdf/{order}', [AdminInvoiceController::class, 'downloadPdf'])
    ->name('invoice.download');
//imp
Route::get('/admin/invoice', [AdminInvoiceController::class, 'show'])->name('invoice.print');

Route::get('/videos/details/{id}', function ($id) {return view('videos.video-details');});

Route::get('/mes-videos-viewees', function() { return view('videos.mes-videos-viewees');});


Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

