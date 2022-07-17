<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeCounter extends Model
{
    use HasFactory;
    protected $table = 'like_count';
    protected $fillable = ['id','type_of_content','post_id','count_of_like'];
}
