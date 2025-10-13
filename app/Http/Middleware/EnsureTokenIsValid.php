<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next)
    {
        // Get token from header or query param
        $token = $request->header('X-API-TOKEN') ?? $request->query('token');

        // Example: predefined token
        $validToken = '1234567890abcdef';

        if (!$token || $token !== $validToken) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or missing API token'
            ], 401);
        }

        return $next($request);
    }
}
