<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowEmbedding
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->headers->remove('X-Frame-Options');
        $response->headers->set('Content-Security-Policy', "frame-ancestors *");
        return $response;
    }
}
