<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FairyImg extends Model
{
    use HasFactory;
    protected $table = 'fairy_img';
    protected $fillable = ['id','refer', 'fairy_id','checked'];
}
