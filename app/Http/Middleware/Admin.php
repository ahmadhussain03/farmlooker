<?php

namespace App\Http\Middleware;

use App\Exceptions\Forbidden;
use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && auth()->user()->user_type != 'admin'){
            throw new Forbidden();
        }

        return $next($request);
    }
}
