<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColoringCat extends Model
{
    use HasFactory;

    protected $table = 'coloring_cat';
    protected $fillable = ['id','cat_id','colored_id'];
    public function cat()
    {
        return $this->hasOne(Cat::class,'id','cat_id');
    }
    public function colored()
    {
        return $this->hasOne(Colored::class,'id','colored_id');
    }
}
