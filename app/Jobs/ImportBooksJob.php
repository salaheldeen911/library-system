<?php

namespace App\Jobs;

use App\Imports\BooksImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class ImportBooksJob implements ShouldQueue
{
    use Queueable;

    protected $filePath;
    protected $authorId;

    public function __construct($filePath, $authorId)
    {
        $this->filePath = $filePath;
        $this->authorId = $authorId;
    }

    public function handle()
    {
        $filePath = public_path('storage/' . $this->filePath);

        if (!file_exists($filePath)) {
            throw new \Exception("File [$filePath] does not exist");
        }

        Excel::import(new BooksImport($this->authorId), $filePath);
    }
}
