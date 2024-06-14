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
            return response()->json(['message' => 'Неправильный логин или пароль'], 401);
        }

        $user = User::where('login', $request->login)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Вход выполнен','token' => $token, 'role' => $user->role], 200);
    }
}
