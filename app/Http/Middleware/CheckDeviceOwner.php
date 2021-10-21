<?php

namespace App\Http\Middleware;

use Closure;

class CheckDeviceOwner
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
        if (!$request->user()->is_admin && $request->route('device')->user_id != $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
