<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
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
            //Super admin role == 2
            //admin role == 1
            //user role == 0
            if ($user->role == '2') {

                return $next($request);

            } else {

                return response()->json([
                    'message' => 'you are not super admin'
                ], 400);

            }

        }
        return $next($request);
    }
}
