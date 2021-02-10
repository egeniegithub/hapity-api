<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Support\Facades\Route;

// If Laravel >= 5.2 then delete 'use' and 'implements' of deprecated Middleware interface.
class AddHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if(Route::currentRouteName() != "widget.index"){
            $response->header("X-Frame-Options", "DENY");
            $response->header("Content-Security-Policy", "frame-ancestors 'none'");
        }

        return $response;
    }
}
