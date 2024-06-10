<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibrarianMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isLibrarian()) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}