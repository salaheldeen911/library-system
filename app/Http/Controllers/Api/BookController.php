<?php

namespace App\Http\Controllers\Api;

use App\Exports\BooksExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportBookRequest;
use App\Http\Requests\SearchBooksRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Jobs\ImportBooksJob;
use App\Models\Book;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        try {
            $books = Book::with('author')
                ->when(auth('sanctum')->user()->hasRole('admin') && request()->has('author_id'), function ($query) {
                    $query->where('author_id', request()->input('author_id'));
                })
                ->get();

            return $this->success('Books retrieved successfully', BookResource::collection($books));
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to retrieve books');
            return $this->failed('Failed to retrieve books');
        }
    }

    public function store(StoreBookRequest $request)
    {
        try {
            $validated = $request->validated();
            $path = 'storage/' . $request->file('cover')->store('covers', 'public');

            $book = Book::create(array_merge($validated, [
                'cover' => $path,
                'author_id' => auth('sanctum')->id(),
            ]));

            return $this->success('Book stored successfully', new BookResource($book), 201);
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to store book');
            return $this->failed('Failed to store book');
        }
    }

    public function show(Book $book)
    {
        try {
            return $this->success('Book retrieved successfully', new BookResource($book->load('author')));
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to retrieve book');
            return $this->failed('Failed to retrieve book');
        }
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        try {
            $this->authorize('update', $book);

            $validated = $request->validated();

            if ($request->hasFile('cover')) {
                File::delete(public_path($book->cover));
                $validated['cover'] = 'storage/' . $request->file('cover')->store('covers', 'public');
            }

            $book->update($validated);

            return $this->success('Book updated successfully', new BookResource($book->refresh()));
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to update book');
            return $this->failed('Failed to update book');
        }
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        try {
            File::delete(public_path($book->cover));
            $book->delete();
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to delete book');
            return $this->failed('Failed to delete book');
        }

        return $this->success('Book deleted successfully');
    }

    public function export()
    {
        try {
            return Excel::download(new BooksExport(auth('sanctum')->id()), 'books.xlsx');
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to export books');
            return $this->failed('Failed to export books');
        }
    }

    public function import(ImportBookRequest $request)
    {
        try {
            $user = auth('sanctum')->user();

            $filePath = $request->file('file')->store('temp', 'public');

            ImportBooksJob::dispatch($filePath, $user->id);

            return $this->success('Books imported successfully');
        } catch (ValidationException $e) {
            return $this->failed('Import failed due to validation errors', $e->errors());
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to import books');
            return $this->failed('Failed to import books');
        }
    }

    public function searchBooks(SearchBooksRequest $request)
    {
        try {
            $query = $request->value;

            $books = Book::whereFullText(['title', 'description'], $query)->get();

            return $this->success('Books retrieved successfully', BookResource::collection($books));
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to retrieve books');
            return $this->failed('Failed to retrieve books');
        }
    }
}
