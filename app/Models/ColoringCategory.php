<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColoringCategory extends Model
{
    use HasFactory;

    protected $table = 'coloring_category';
    protected $fillable = ['id','category_id','colored_id'];
    public function categories()
    {
        return $this->hasOne(Categories::class,'id','category_id');
    }
    public function colored()
    {
        return $this->hasOne(Colored::class,'id','colored_id');
    }
}
