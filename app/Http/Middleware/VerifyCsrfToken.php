<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log; // Import the Log facade


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
         Log::info('CSRF Middleware reached for request:', [
             'method' => $request->method(),
             'url' => $request->fullUrl(),
             'headers' => $request->headers->all(),
         ]);

         // Allow all requests to proceed without CSRF verification
         return $next($request);
     }
}
