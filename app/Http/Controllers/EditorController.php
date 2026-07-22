<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\Book\BookService;
use App\Services\Design\DesignService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EditorController extends Controller
{
    public function __construct(
        protected BookService $bookService,
        protected DesignService $designService,
    ) {}

    public function create(): View
    {
        return view('editor.create');
    }

    public function show(Book $book): View
    {
        $this->authorize('view', $book);
        $book->load('chapters.sections', 'design');
        return view('pages.editor', compact('book'));
    }

    public function focus(Book $book): View
    {
        $this->authorize('view', $book);
        $book->load('chapters.sections', 'design');
        return view('pages.editor', ['book' => $book, 'focusMode' => true]);
    }

    public function design(Book $book): View
    {
        $this->authorize('update', $book);
        $templates = $this->designService->getAvailableTemplates();
        return view('editor.design', compact('book', 'templates'));
    }

    public function preview(Book $book): View
    {
        $this->authorize('view', $book);
        return view('editor.preview', compact('book'));
    }

    public function export(Book $book): View
    {
        $this->authorize('view', $book);
        return view('editor.export', compact('book'));
    }

    public function settings(Book $book): View
    {
        $this->authorize('update', $book);
        return view('editor.settings', compact('book'));
    }

    public function aiExplore(): View
    {
        return view('editor.ai-explore');
    }

    public function templates(): View
    {
        return view('editor.templates');
    }
}
