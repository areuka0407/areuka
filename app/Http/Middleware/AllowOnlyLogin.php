<?php

namespace App\Http\Middleware;

use Closure;

class AllowOnlyLogin
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
        if(!user()) return redirect()->route("users.login")->with("flash_message", LOGIN_MESSAGE);
        return $next($request);
    }
}
