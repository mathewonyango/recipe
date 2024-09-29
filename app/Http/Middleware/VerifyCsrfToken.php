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
        // Check if the request URI matches the except array
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return $next($request); // Skip CSRF verification
            }
        }

        // Proceed with CSRF verification for other routes
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            // Perform CSRF verification logic here, if necessary
            // e.g., Check CSRF token in the request
        }

        return $next($request);
    }

}
