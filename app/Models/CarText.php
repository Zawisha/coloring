<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarText extends Model
{
    use HasFactory;
    protected $fillable = ['id','text','city_id'];
}
