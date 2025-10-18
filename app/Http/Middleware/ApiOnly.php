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
        // Always allow requests with proper API headers (mobile app)
        // Check for Accept: application/json header OR Bearer token
        if ($request->expectsJson() || $request->bearerToken() || $request->hasHeader('X-App-Identifier')) {
            return $next($request);
        }

        // Block browser requests (no Accept header, no token, no app identifier)
        return response()->json([
            'success' => false,
            'message' => 'This endpoint is only accessible via API. Please use the mobile application.',
            'error' => 'browser_access_forbidden',
        ], 403);
    }
}
