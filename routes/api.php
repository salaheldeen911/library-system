<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('categories', CategoryController::class);
        Route::post('authors', [AuthorController::class, 'store']);
    });

    Route::middleware('role:author')->group(function () {
        Route::get('books-export', [BookController::class, 'export']);
        Route::post('books-import', [BookController::class, 'import']);
        Route::apiResource('books', BookController::class);
    });
});

Route::get('books-search', [BookController::class, 'searchBooks']);
