<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Colored extends Model
{
    protected $table = 'colored';
    protected $fillable = ['id','name', 'img','description', 'from_user', 'category','published','slug'];
    public function categories()
    {
        return $this->belongsToMany(Categories::class,'coloring_category','colored_id','category_id');
    }
    public function like()
    {
//        return $this->hasOne(Like::class,'post_id','id');
        return $this->belongsToMany(Like::class,'Like');
    }
}
