<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\Book\BookService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BooksController extends Controller
{
    public function __construct(
        protected BookService $bookService,
    ) {}

    public function index(): View
    {
        $books = auth()->user()->books()
            ->with('chapters', 'design')
            ->latest()
            ->paginate(12);
        
        return view('books.index', compact('books'));
    }

    public function show(Book $book): View
    {
        $this->authorize('view', $book);
        $book->increment('view_count');
        $book->load('chapters.sections', 'user', 'design');
        
        return view('books.show', compact('book'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $book = $this->bookService->create($validated);

        return redirect()->route('editor.show', $book);
    }
}
