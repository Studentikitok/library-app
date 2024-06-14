<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function createAuthor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:authors',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } 

        $author = Author::create([
            'name' => $request->input('name')
        ]);

        return response()->json(['message' => 'Автор создан', $author], 201);
    }
}