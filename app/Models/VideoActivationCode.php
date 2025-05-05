<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoActivationCode extends Model
{

    protected $fillable = ['user_id', 'video_id', 'code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}


