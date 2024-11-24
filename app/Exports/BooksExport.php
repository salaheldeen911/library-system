<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    protected $authorId;

    public function __construct($authorId)
    {
        $this->authorId = $authorId;
    }

    public function headings(): array
    {
        return [
            'title',
            'description',
            'published_at',
            'bio',
            'cover',
        ];
    }

    public function collection()
    {
        return Book::select([
            'title',
            'description',
            'published_at',
            'bio',
            'cover',
        ])->where('author_id', $this->authorId)->get();
    }
}
