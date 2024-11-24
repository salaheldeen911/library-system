<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('published_at');
            $table->text('bio');
            $table->string('cover'); // Path to the book cover image
            $table->foreignId('author_id')->constrained('users')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();

            $table->fullText(['title', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
