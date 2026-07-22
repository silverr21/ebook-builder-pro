<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\Book\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(protected BookService $bookService) {}

    public function index(Request $request): JsonResponse
    {
        $query = auth()->user()->books();
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $books = $query->with('chapters', 'design')
            ->paginate($request->per_page ?? 15);

        return response()->json($books);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'tags' => 'nullable|json',
        ]);

        $validated['user_id'] = auth()->id();
        $book = $this->bookService->create($validated);

        return response()->json($book, 201);
    }

    public function show(Book $book): JsonResponse
    {
        $this->authorize('view', $book);
        
        $book->load('chapters.sections', 'design', 'user');
        $book->loadCount('chapters as chapters_count');
        
        return response()->json([
            'book' => $book,
            'statistics' => $this->bookService->getStatistics($book),
            'toc' => $this->bookService->getToc($book),
        ]);
    }

    public function update(Request $request, Book $book): JsonResponse
    {
        $this->authorize('update', $book);
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'tags' => 'nullable|json',
            'cover_image' => 'nullable|image',
        ]);

        if ($request->hasFile('cover_image')) {
            // Handle image upload
        }

        $book = $this->bookService->update($book, $validated);

        return response()->json($book);
    }

    public function destroy(Book $book): JsonResponse
    {
        $this->authorize('delete', $book);
        $book->delete();
        return response()->json(null, 204);
    }

    public function publish(Book $book): JsonResponse
    {
        $this->authorize('update', $book);
        $this->bookService->publish($book);
        return response()->json(['message' => 'Book published', 'book' => $book]);
    }

    public function statistics(Book $book): JsonResponse
    {
        $this->authorize('view', $book);
        return response()->json($this->bookService->getStatistics($book));
    }
}
