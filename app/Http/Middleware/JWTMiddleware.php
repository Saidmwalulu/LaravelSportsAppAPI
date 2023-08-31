<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $message = '';

        try {
            JWTAuth::parseToken()->authenticate();
            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $message = 'token expired';
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $message = 'invalid token';
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $message = 'provide token';
        }

        return response()->json([
            'success' => false,
            'message' => $message
        ], 404);

    }
}
