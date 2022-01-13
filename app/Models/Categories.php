<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Categories extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['id','name'];
    public function colored()
    {
        return $this->belongsToMany(Colored::class);
    }
    public function fairy()
    {
        return $this->belongsToMany(Fairy::class);
    }
}
