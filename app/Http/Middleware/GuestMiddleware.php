<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Вы уже авторизованы.'], 403);
        }

        return $next($request);
    }
}