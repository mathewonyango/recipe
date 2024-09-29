<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        // Generate CSRF token if not already set in the session
        if (!$request->session()->has('_token')) {
            // Generate a new token
            $token = Session::token();
            // Store the token in the session
            $request->session()->put('_token', $token);
        }

        // Get the CSRF token
        $csrfToken = $request->session()->get('_token');

        // Set CSRF token in the response headers
        $response = $next($request);
        $response->headers->set('X-CSRF-Token', $csrfToken);

        return $response;
    }
}
