<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    use HasFactory;
    protected $table = 'video_category';
    protected $fillable = ['id','category_id','video_id'];
    public function categories()
    {
        return $this->hasOne(Categories::class,'id','category_id');
    }
    public function video()
    {
        return $this->hasOne(Video::class,'id','video_id');
    }
}
