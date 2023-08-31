<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user) {
            //admin role == 1
            //user role == 0
            if ($user->role == '1') {

                return $next($request);

            } else {

                return response()->json([
                    'message' => 'you are not an admin'
                ], 400);

            }

        }
        return $next($request);
    }
}
