<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $table = 'video';
    protected $fillable = ['id','name', 'video_link','description', 'from_user', 'published','image'];
    public function categories()
    {
        return $this->belongsToMany(Categories::class,'video_category','video_id','category_id');
    }
}
