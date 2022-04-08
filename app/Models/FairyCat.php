<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FairyCat extends Model
{
    use HasFactory;

    protected $table = 'fairy_cat';
    protected $fillable = ['id','cat_id','fairy_id'];
    public function cat()
    {
        return $this->hasOne(Categories::class,'id','cat_id');
    }
    public function fairy()
    {
        return $this->hasOne(Fairy::class,'id','fairy_id');
    }
}
