<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCat extends Model
{
    use HasFactory;
    protected $table = 'video_cat';
    protected $fillable = ['id','cat_id','video_id'];
    public function cat()
    {
        return $this->hasOne(Categories::class,'id','cat_id');
    }
    public function video()
    {
        return $this->hasOne(Video::class,'id','video_id');
    }
}
