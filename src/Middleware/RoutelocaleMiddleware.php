<?php

namespace Crumby\Routelocale\Middleware;
//use Crumby\Routelocale\Routelocale as Routelocale;

use Closure;
use Illuminate\Routing\Redirector;

class RoutelocaleMiddleware
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
        \App::setLocale(\Routelocale::getLocaleFromRoute());
        return $next($request);;
    }
}
