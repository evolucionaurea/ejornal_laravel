<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class LogRouteHits
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
        $uri = $request->path();
        $key = "route_hits:{$uri}";
        Cache::increment($key);
        // keep a sorted set of route URIs for listing
        $routes = Cache::get('routes_list', []);
        if (!in_array($uri, $routes)) {
            $routes[] = $uri;
            Cache::forever('routes_list', $routes);
        }
        return $next($request);
    }
}
