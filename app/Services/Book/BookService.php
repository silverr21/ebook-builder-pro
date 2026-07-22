<?php

namespace App\Services\Book;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Section;
use App\Models\Design;
use Illuminate\Support\Str;

class BookService
{
    public function create(array $data): Book
    {
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        }
        return Book::create($data);
    }

    public function update(Book $book, array $data): Book
    {
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $book->update($data);
        return $book;
    }

    public function createChapter(Book $book, array $data): Chapter
    {
        $data['book_id'] = $book->id;
        $data['slug'] = Str::slug($data['title']);
        return Chapter::create($data);
    }

    public function createSection(Chapter $chapter, array $data): Section
    {
        $data['chapter_id'] = $chapter->id;
        $section = Section::create($data);
        $section->updateWordCount();
        return $section;
    }

    public function getStatistics(Book $book): array
    {
        $chapters = $book->chapters()->count();
        $sections = $book->sections()->count();
        $wordCount = $book->wordCount();
        $publishedChapters = $book->chapters()->where('status', 'published')->count();
        
        return [
            'chapters' => $chapters,
            'sections' => $sections,
            'word_count' => $wordCount,
            'published_chapters' => $publishedChapters,
            'reading_time' => ceil($wordCount / 200), // približne minut
            'view_count' => $book->view_count,
        ];
    }

    public function incrementViewCount(Book $book): void
    {
        $book->increment('view_count');
    }

    public function publish(Book $book): void
    {
        $book->update([
            'status' => 'published',
            'published_at' => now()
        ]);
    }

    public function archive(Book $book): void
    {
        $book->update(['status' => 'archived']);
    }

    public function getToc(Book $book): array
    {
        $toc = [];
        foreach ($book->chapters as $chapter) {
            $sections = $chapter->sections->map(fn($s) => [
                'id' => $s->id,
                'title' => $s->title,
            ])->toArray();
            
            $toc[] = [
                'id' => $chapter->id,
                'title' => $chapter->title,
                'sections' => $sections,
            ];
        }
        return $toc;
    }

    public function calculateReadingTime(Book $book): int
    {
        return ceil($book->wordCount() / 200);
    }

    public function updateWordCounts(Book $book): void
    {
        foreach ($book->sections as $section) {
            $section->updateWordCount();
        }
    }
}
