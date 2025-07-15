<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // dd([
        //     'user_role' => auth()->user()->role,
        //     'allowed_roles' => $roles
        // ]);
        if (!in_array(auth()->user()->role, $roles)) {
            return redirect()->back()->withErrors(['email' => 'Unauthorized role. Please contact support.']);
        }
        return $next($request);
    }
}
