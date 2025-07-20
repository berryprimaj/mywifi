<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        // Check if user is admin
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Admin privileges required.'
            ], 403);
        }

        return $next($request);
    }
} 