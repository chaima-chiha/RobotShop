<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VideoFilee extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'name',
        'file_path',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }
    
}

