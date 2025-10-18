<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictToMobileApp
{
    /**
     * Handle an incoming request - Enhanced security with custom header validation
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for custom app identifier header (add this to your Flutter app)
        $appIdentifier = $request->header('X-App-Identifier');
        $expectedIdentifier = config('app.mobile_app_identifier', 'SalesPulse-Mobile-App');
        
        // Allow if custom header matches
        if ($appIdentifier === $expectedIdentifier) {
            return $next($request);
        }

        // Allow requests with valid Bearer token (Sanctum)
        if ($request->bearerToken()) {
            return $next($request);
        }

        // Block browser/unauthorized access
        return response()->json([
            'success' => false,
            'message' => 'Access denied. This API is restricted to the SalesPulse mobile application only.',
            'error' => 'unauthorized_client',
        ], 403);
    }
}
