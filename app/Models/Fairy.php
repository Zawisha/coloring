<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fairy extends Model
{
    use HasFactory;
    protected $table = 'fairy';
    protected $fillable = ['id','name', 'img_title','description', 'from_user', 'published'];
    public function categories()
    {
        return $this->belongsToMany(Categories::class,'fairy_category','fairy_id','category_id');
    }
}
