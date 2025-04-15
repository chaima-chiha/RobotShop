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
    $ordered = $this->orderItems()->sum('quantity');
    $cart = $this->cart()->sum('quantity');
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
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }




}



