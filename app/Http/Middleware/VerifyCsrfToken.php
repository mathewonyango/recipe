<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

     protected $except = [
        'api/*',      // Exclude all API routes
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Allow all requests without CSRF token for the specified routes
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return $next($request); // Skip CSRF verification for excluded routes
            }
        }

        // For other requests, you can choose to not perform CSRF checks at all
        return $next($request);
    }

}
