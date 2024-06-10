<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('login', 'password'))) {
            return response()->json(['message' => 'Invalid login or password'], 401);
        }

        $user = User::where('login', $request->login)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'role' => $user->role], 200);
    }
}
