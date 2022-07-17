<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $table = 'like';
    protected $fillable = ['id','type_of_like','user_id','type_of_content','post_id'];

    public function colored()
    {
//        return $this->belongsToMany(Colored::class);
        return $this->hasOne(Colored::class,'post_id','id');

    }

}
