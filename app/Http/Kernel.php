<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Protected properties and methods here


    protected $middlewareGroups = [
        'web' => [
            // ...
        ],

        'api' => [
            'throttle:api',
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'bindings',
        ],
    ];



}
