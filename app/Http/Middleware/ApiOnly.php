<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiOnly
{
    /**
     * Handle an incoming request - Block browser access, allow API clients only
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow requests with API tokens (Sanctum)
        if ($request->bearerToken()) {
            return $next($request);
        }

        // Allow requests with Accept: application/json header (API clients)
        if ($request->expectsJson()) {
            return $next($request);
        }

        // Allow requests to specific public endpoints (like login, register)
        $publicEndpoints = [
            'api/register',
            'api/login',
            'api/forgot-password',
            'api/reset-password',
        ];

        if (in_array($request->path(), $publicEndpoints)) {
            return $next($request);
        }

        // Block all browser requests
        return response()->json([
            'success' => false,
            'message' => 'This endpoint is only accessible via API. Please use the mobile application.',
            'error' => 'browser_access_forbidden',
        ], 403);
    }
}
