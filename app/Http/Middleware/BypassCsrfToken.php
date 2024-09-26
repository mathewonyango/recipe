<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BypassCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('api/*')) {
            // Generate CSRF token for API requests if not present
            if (!$request->hasHeader('X-CSRF-TOKEN')) {
                $csrfToken = csrf_token();
                return response()->json(['csrf_token' => $csrfToken]);
            }
        }

        return $next($request);
    }
}
