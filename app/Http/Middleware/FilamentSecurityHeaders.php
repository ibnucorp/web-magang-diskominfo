<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilamentSecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // route Filament
        if (str_starts_with($request->path(), 'user')) {
            $response->headers->set(
                'Content-Security-Policy',
                "upgrade-insecure-requests;"
            );
        }

        return $response;
    }
}
