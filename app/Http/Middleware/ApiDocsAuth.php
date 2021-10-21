<?php

namespace App\Http\Middleware;

use Closure;

class ApiDocsAuth
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
        if ($request->is('*api-doc*')) {
            if ($request->getUser() !== 'timedoor' || $request->getPassword() !== 'Mobigps@2021!') {
                $headers = ['WWW-Authenticate' => 'Basic'];

                return abort(401, 'Unauthorized', $headers);
            }
        }

        return $next($request);
    }
}
