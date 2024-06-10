<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Librarian\BookController;
use App\Http\Controllers\Librarian\AuthorController;
use App\Http\Controllers\Librarian\GenreController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::post('/user/avatar', [UserController::class, 'updateAvatar']);
    Route::middleware('admin')->group(function () {
        Route::post('/admin/create-librarian', [UserManagementController::class, 'createLibrarian']);
    });
    Route::middleware('librarian')->group(function () {
        Route::post('/librarian/create-book', [BookController::class, 'createBook']);
        Route::post('/librarian/create-author', [AuthorController::class, 'createAuthor']);
        Route::post('/librarian/create-genre', [GenreController::class, 'createGenre']);
    });
});