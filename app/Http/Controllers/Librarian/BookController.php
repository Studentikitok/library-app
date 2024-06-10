<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

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

    public function editBook(Request $request, $bookId)
    {
        $book = Book::findOrFail($bookId);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'isbn' => 'sometimes|string|unique:books,isbn' . $book->id,
            'description' => 'sometimes|string',
            'language' => 'sometimes|string|max:255',
            'authors' => 'sometimes|array',
            'authors.*' => 'exists:authors,id',
            'genres' => 'sometimes|array',
            'genres.*' => 'exists:genres,id',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
            $book->cover_image = $coverImagePath;
        }

        $book->update($request->only(['title', 'isbn', 'description', 'language']));

        if ($request->has('authors')) {
            $book->authors()->sync($request->authors);
        }

        if ($request->has('genres')) {
            $book->genres()->sync($request->genres);
        }

        return response()->json($book, 200);
    }

    public function deleteBook($bookId)
    {
        $book = Book::findOrFail($bookId);

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->authors()->detach();
        $book->genres()->detach();

        $book->delete();

        return response()->json(['message' => 'Книга удалена.'], 200);
    }
}