<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ProductVideo extends Pivot
{
    use HasFactory;

    protected $table = 'product_video';

    public $incrementing = true;

    protected $fillable = [
        'product_id',
        'video_id'
    ];
}
