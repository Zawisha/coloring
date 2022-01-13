<?php

namespace App\Http\Middleware;

use App\Models\Admins;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!(Auth::user()))
        {
            return redirect('/login');
        }
        else
        {
            $user = Auth::user();
            $admin = Admins::where('user_id','=',$user->id)->where('user_permission_id','=',1)->get();
            if ($admin->isEmpty()) {
                return redirect('/');
            }
            else
            {
                return $next($request);
            }
        }

    }
}
