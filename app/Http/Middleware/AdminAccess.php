<?php

namespace App\Http\Middleware;

use Closure;

class AdminAccess
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
        if (!is_null($request->user())) {
            $user = $request->user();
            if ($user->hasRole(SUPER_ADMIN_ROLE_ID)) {
                return $next($request);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('login');
        }
    }
}
