<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FairyPage extends Model
{
    use HasFactory;
    protected $table = 'fairy_page';
    protected $fillable = ['id','fairy_id', 'description','page'];
}
