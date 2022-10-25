<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VkSearchGroup extends Model
{
    use HasFactory;
    protected $table = 'vk_search_group';
    protected $fillable = ['id','group_id', 'technology_id','last_post'];
}
