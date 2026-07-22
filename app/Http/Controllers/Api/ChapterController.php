<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Chapter;
use App\Services\Book\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function __construct(protected BookService $bookService) {}

    public function index(Book $book): JsonResponse
    {
        $this->authorize('view', $book);

        $chapters = $book->chapters()->with('sections')->get();

        return response()->json($chapters);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $book = Book::findOrFail($validated['book_id']);
        $this->authorize('update', $book);

        $nextOrder = $book->chapters()->max('order') + 1;

        $chapter = $this->bookService->createChapter($book, [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'order' => $nextOrder,
        ]);

        return response()->json($chapter->load('sections'), 201);
    }

    public function show(Chapter $chapter): JsonResponse
    {
        $this->authorize('view', $chapter->book);

        $chapter->load('sections');

        return response()->json($chapter);
    }

    public function update(Request $request, Chapter $chapter): JsonResponse
    {
        $this->authorize('update', $chapter->book);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:draft,published',
            'order' => 'sometimes|integer|min:0',
        ]);

        $chapter->update($validated);

        return response()->json($chapter->load('sections'));
    }

    public function destroy(Chapter $chapter): JsonResponse
    {
        $this->authorize('delete', $chapter->book);

        $chapter->delete();

        return response()->json(null, 204);
    }

    public function reorder(Request $request, Book $book): JsonResponse
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'chapters' => 'required|array',
            'chapters.*.id' => 'required|exists:chapters,id',
            'chapters.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['chapters'] as $item) {
            Chapter::where('id', $item['id'])
                ->where('book_id', $book->id)
                ->update(['order' => $item['order']]);
        }

        return response()->json(['message' => 'Chapters reordered']);
    }
}
