<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
         'product_id',
         'quantity'
        ];


     // Obtenir l'utilisateur qui possède le panier.
    public function user()
    {
        return $this->belongsTo(User::class);
    }


     // Obtenir le produit associé au panier.
    public function product()
{
    return $this->belongsTo(Product::class);
}

}
