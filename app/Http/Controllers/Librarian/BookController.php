<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function createBook(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'isbn' => 'required|string|unique:books',
            'description' => 'nullable|string',
            'language' => 'required|string|max:255',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $coverImagePath = $request->hasFile('cover_image') ? $request->file('cover_image')->store('covers', 'public') : null;

        $book = Book::create([
            'title' => $request->title,
            'cover_image' => $coverImagePath,
            'isbn' => $request->isbn,
            'description' => $request->description,
            'language' => $request->language,
        ]);

        $book->authors()->sync($request->authors);
        $book->genres()->sync($request->genres);

        return response()->json($book, 201);
    }
}
