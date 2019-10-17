<?php

namespace App\Http\Middleware;

use Closure;

class AllowOnlyAuth
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
        if(!admin()) return redirect()->route("users.login")->with("flash_message", AUTH_MESSAGE);
        return $next($request);
    }
}
