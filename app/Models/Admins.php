<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Admins extends Model
{
    use HasFactory;
    protected $table = 'admins';
    protected $fillable = ['id','user_id','user_permission_id'];

    public static function isAdmin(){
        $user = Auth::user();
        $id=Admins::where('user_id', '=', $user->id)->where('user_permission_id', '=', '1')
            ->get();
        if ($id->isEmpty()) {
            return false;
        }
        else
        {
            return true;
        }
    }

}
