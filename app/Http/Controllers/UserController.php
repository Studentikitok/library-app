<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = auth()->user();
        $path = $request->file('avatar')->store('avatars', 'public');

        // Удаление старого аватара, если он есть
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => $path]);

        return response()->json(['avatar_url' => $user->avatar_url], 200);
    }

    public function getUserProfile(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }
}
