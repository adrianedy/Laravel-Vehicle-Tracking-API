<?php

namespace App\Http\Middleware;

use Closure;

class ApiVersioning
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        config(['app.api.version' => $guard]);
        
        return $next($request);
    }
}
