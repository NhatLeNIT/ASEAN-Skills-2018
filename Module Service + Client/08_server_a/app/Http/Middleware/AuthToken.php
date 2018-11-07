<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class AuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $bearer = $request->header('Authorization');
        if (!$bearer) return unauthorized();
        $user = User::get();
        if (!$user ||  'Bearer ' .$user->token != $bearer) return unauthorized();
        return $next($request);
    }
}
