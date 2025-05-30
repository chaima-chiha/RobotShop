<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_path',
        'thumbnail',
        'duration',
        'category_id',
        'niveau',
        'price'
    ];

    protected $casts = [
        'price' => 'float',
    ];

    // Relation avec la catégorie si nécessaire
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accesseur pour l'URL de la vidéo
    public function getVideoUrlAttribute()
    {
        return $this->video_path ? Storage::url($this->video_path) : null;
    }

    // Accesseur pour l'URL de la miniature
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? Storage::url($this->thumbnail) : null;
    }

    // Relation avec les produits
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withTimestamps();
    }
    public function files()
    {
        return $this->hasMany(VideoFilee::class);
    }

    public function videoViews()
    {
        return $this->hasMany(VideoView::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function activationCodes()
{
    return $this->hasMany(VideoActivationCode::class);
}

}
