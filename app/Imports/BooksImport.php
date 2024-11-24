<?php

namespace App\Imports;

use App\Models\Book;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $authorId;

    public function __construct($authorId)
    {
        $this->authorId = $authorId;
    }

    public function collection(Collection $books)
    {
        foreach ($books as $book) {
            $validator = Validator::make($book->toArray(), $this->rules());

            if ($validator->fails()) {
                Log::error('Validation failed for book', $book->toArray());
                Log::error('Validation errors', $validator->errors()->all());

                continue;
            }

            $book = Book::firstOrCreate(
                ['title' => $book['title'], 'author_id' => $this->authorId],
                [
                    'description' => $book['description'],
                    'published_at' => $book['published_at'],
                    'bio' => $book['bio'],
                    'cover' => $book['cover'],
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:100',
            'description' => 'required|string|min:5|max:500',
            'published_at' => 'required|date',
            'bio' => 'required|string|min:5|max:500',
            'cover' => 'required|string', // path
        ];
    }
}
