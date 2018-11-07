<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class AuthIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::get();
        if($user->role != 'ADMIN') return unauthorized();
        return $next($request);
    }
}
