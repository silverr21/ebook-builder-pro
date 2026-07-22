<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = auth()->user();
        
        // Eager load all books with their chapters and sections for statistics
        $books = $user->books()
            ->with(['chapters.sections'])
            ->latest()
            ->get();
        
        // === Basic Stats ===
        $totalBooks = $books->count();
        $publishedBooks = $books->where('status', 'published')->count();
        $draftBooks = $books->where('status', 'draft')->count();
        $totalViews = $books->sum('view_count');
        
        // === Total Words ===
        $totalWords = 0;
        foreach ($books as $book) {
            foreach ($book->chapters as $chapter) {
                foreach ($chapter->sections as $section) {
                    $totalWords += $section->word_count;
                }
            }
        }
        
        // === Weekly Stats (last 7 days) ===
        $weekAgo = now()->subDays(7);
        $wordsThisWeek = Section::whereHas('chapter.book', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('created_at', '>=', $weekAgo)->sum('word_count');
        
        $chaptersThisWeek = Chapter::whereHas('book', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('created_at', '>=', $weekAgo)->count();
        
        // === Writing Streak ===
        $writingDays = Section::whereHas('chapter.book', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->select(DB::raw('DATE(created_at) as date'))
        ->distinct()
        ->orderBy('date', 'desc')
        ->pluck('date');
        
        $streak = 0;
        $checkDate = now()->format('Y-m-d');
        foreach ($writingDays as $day) {
            if ($day === $checkDate) {
                $streak++;
                $checkDate = date('Y-m-d', strtotime($checkDate . ' -1 day'));
            } else {
                break;
            }
        }
        
        // === Books with progress ===
        $bookProgress = [];
        foreach ($books as $book) {
            $chaptersCount = $book->chapters->count();
            $wordCount = $book->chapters->sum(function ($chapter) {
                return $chapter->sections->sum('word_count');
            });
            
            $bookProgress[$book->id] = [
                'chapters_count' => $chaptersCount,
                'word_count' => $wordCount,
                'status' => $book->status,
                'updated_at' => $book->updated_at,
                'updated_diff' => $book->updated_at->diffForHumans(),
            ];
        }
        
        // === Activity Feed (last 10 actions) ===
        $recentActivity = collect();
        
        // Recent chapters
        $recentChapters = Chapter::whereHas('book', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('book')->latest()->take(5)->get();
        
        foreach ($recentChapters as $chapter) {
            $recentActivity->push([
                'type' => 'chapter',
                'icon' => '📖',
                'text' => "Nová kapitola: <strong>{$chapter->title}</strong>",
                'book' => $chapter->book->title,
                'time' => $chapter->created_at->diffForHumans(),
                'created_at' => $chapter->created_at,
                'url' => route('editor.show', $chapter->book),
            ]);
        }
        
        // Recent sections (written content)
        $recentSections = Section::whereHas('chapter.book', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('chapter.book')->latest()->take(5)->get();
        
        foreach ($recentSections as $section) {
            $words = $section->word_count;
            $recentActivity->push([
                'type' => 'writing',
                'icon' => '✍️',
                'text' => "Napísané <strong>{$words} slov</strong>",
                'book' => $section->chapter->book->title,
                'time' => $section->created_at->diffForHumans(),
                'created_at' => $section->created_at,
                'url' => route('editor.show', $section->chapter->book),
            ]);
        }
        
        // Recent published books
        $recentPublished = $books->where('status', 'published')->sortByDesc('published_at')->take(3);
        foreach ($recentPublished as $book) {
            $recentActivity->push([
                'type' => 'publish',
                'icon' => '📤',
                'text' => "Publikovaná kniha: <strong>{$book->title}</strong>",
                'book' => $book->title,
                'time' => $book->published_at->diffForHumans(),
                'created_at' => $book->published_at,
                'url' => route('books.show', $book),
            ]);
        }
        
        // Sort by created_at and take latest 8
        $recentActivity = $recentActivity->sortByDesc('created_at')->take(8)->values();
        
        // === Weekly chart data ===
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayWords = Section::whereHas('chapter.book', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereDate('created_at', $date)
            ->sum('word_count');
            
            $chartData[] = [
                'day' => now()->subDays($i)->format('D'),
                'words' => $dayWords,
            ];
        }
        
        // Get recent books (last 6)
        $recentBooks = $books->take(6);
        
        return view('pages.dashboard', compact(
            'totalBooks',
            'publishedBooks',
            'draftBooks',
            'totalWords',
            'totalViews',
            'wordsThisWeek',
            'chaptersThisWeek',
            'streak',
            'bookProgress',
            'recentActivity',
            'chartData',
            'recentBooks'
        ));
    }
}
