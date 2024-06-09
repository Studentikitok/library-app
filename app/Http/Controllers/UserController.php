<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = $request->user();
        $avatar = $request->file('avatar');
        $avatarPath = $avatar->store('avatars', 'public');

        // Удаление старого аватара, если он есть
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = $avatarPath;
        $user->save();

        return response()->json(['message' => 'Avatar uploaded successfully', 'avatar_url' => asset('storage/' . $avatarPath)], 200);
    }

    public function getUserProfile(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }
}
