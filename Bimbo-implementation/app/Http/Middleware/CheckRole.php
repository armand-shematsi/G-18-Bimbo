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
        \Log::info('CheckRole middleware', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role ?? null,
            'allowed_roles' => $roles
        ]);
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Unauthorized role: ' . (auth()->user()->role ?? 'none'));
        }
        return $next($request);
    }
}
