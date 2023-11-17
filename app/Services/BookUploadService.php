<?php

namespace App\Services;

use App\Models\BookPage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookUploadService
{
    public static function upload($book, $pages, $directory = 'books/', $visibility = 'public'): void
    {
        // Check if there is a directory for the book
        if (! Storage::disk('spaces')->exists('rigel/'.$directory.$book->title)) {
            // Create a new directory for the new book
            Storage::disk('spaces')->makeDirectory('rigel/'.$directory.$book->title);
        }

        // Loop pages and upload them
        foreach ($pages as $page) {
            Log::info('Uploading page: '.$page->getClientOriginalName());

            // Use Storage to put the file on Spaces
            Storage::disk('spaces')->put('rigel/'.$directory.$book->title.'/'.$page->getClientOriginalName(), $page->encode(), $visibility);

            BookPage::create([
                'book_id' => $book->id,
                'path' => Storage::disk('spaces')->url('rigel/'.$directory.$book->title.'/'.$page->getClientOriginalName()),
                'page_number' => $page->getClientOriginalName(),
            ]);
        }
    }
}
