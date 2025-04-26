<?php


       namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Support\Facades\Storage;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'refrence',
        'description',
        'price',
        'promotion',
        'stock',
        'category_id',
        'image',
    ];

    protected $appends = ['available_stock',];



    public function getAvailableStockAttribute()
    {
        // Quantité totale commandée pour ce produit
        $ordered = $this->orderItems()->sum('quantity');

        // Quantité totale dans le panier pour ce produit
        $cart = $this->productcartItems()->sum('quantity');

        // Stock disponible = stock total - quantité commandée - quantité dans le panier
        return max(0, $this->stock - $ordered - $cart);
    }

    // Relation avec la catégorie
    public function category()
    {
        return $this->belongsTo(Category::class);

    }

    // Ajout d'un accesseur pour l'URL de l'image
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
    // Relation avec les vidéos
    public function videos()
    {
        return $this->belongsToMany(Video::class)
                    ->withTimestamps();
    }

    //relation avec le panier

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productcartItems()
    {
        return $this->hasMany(CartItem::class);
    }


}



