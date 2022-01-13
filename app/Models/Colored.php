<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Colored extends Model
{
    protected $table = 'colored';
    protected $fillable = ['id','name', 'img','description', 'from_user', 'category','published'];
    public function categories()
    {
        return $this->belongsToMany(Categories::class,'coloring_category','colored_id','category_id');
    }
}
