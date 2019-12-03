<?php

namespace App\Http\Middleware;

use Closure;

class CheckMaintenanceMode
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
        $ip = $request->ip();
        
        $check_maintenance = explode(',', env('MAINTENANCE_MODE_IPS'));
        $maintenance_mode = env('MAINTENANCE_MODE');
        if ($maintenance_mode == false || in_array($ip, $check_maintenance)) {
            return $next($request);
        } else {
            return response()->make(view('maintenancemode.index'));
        }

    }
}
