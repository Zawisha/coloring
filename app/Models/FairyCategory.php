<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FairyCategory extends Model
{
    use HasFactory;

    protected $table = 'fairy_category';
    protected $fillable = ['id','category_id','fairy_id'];
    public function categories()
    {
        return $this->hasOne(Categories::class,'id','category_id');
    }
    public function fairy()
    {
        return $this->hasOne(Fairy::class,'id','fairy_id');
    }
}
