<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($role == 'super_admin' && !Auth::user()->hasRole('super_admin')) {
            abort(403);
        }
        if ($role == 'company_admin' && !Auth::user()->hasRole('company_admin') ) {
            abort(403);
        }
        if ($role == 'employee' &&  !Auth::user()->hasRole('employee')) {
            abort(403);
        }

        return $next($request);
    }
}
