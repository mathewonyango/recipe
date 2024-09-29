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



   public function handle(Request $request, Closure $next): Response
    {
        // Log the incoming request for testing
        // Log::info('CSRF Middleware reached for request:', [
        //     'method' => $request->method(),
        //     'url' => $request->fullUrl(),
        //     'headers' => $request->headers->all(),
        // ]);

        // Return a JSON response for testing purposes
        return response()->json([
            'message' => 'CSRF Middleware reached',
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
        ]);

        // Uncomment the line below if you want to allow the request to proceed afterward
        // return $next($request);
    }
}
