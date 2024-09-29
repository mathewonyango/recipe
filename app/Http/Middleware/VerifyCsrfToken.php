<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken
{
    protected $except = [
        // You can specify any routes to be excluded from CSRF verification here if needed
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request method is one that typically requires CSRF protection
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            // Check for CSRF token in the request headers
            if (!$request->hasHeader('X-CSRF-Token')) {
                // Return JSON response indicating missing CSRF token
                return response()->json([
                    'message' => 'CSRF token is missing',
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'headers' => $request->headers->all(),
                ], 419); // 419: unknown status code for CSRF token missing
            }
        }

        // Proceed with the request
        return $next($request);
    }
}
