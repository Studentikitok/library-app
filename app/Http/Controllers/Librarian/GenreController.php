<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    public function createGenre(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:genres',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } 

        $genre = Genre::create([
            'name' => $request->input('name')
        ]);
        
        return response()->json(['message' => 'Жанр создан', $genre], 201);
    }
}