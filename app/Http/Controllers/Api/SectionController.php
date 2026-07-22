<?php

namespace App\Http\Controllers\Api;

use App\Models\Chapter;
use App\Models\Section;
use App\Services\Book\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function __construct(protected BookService $bookService) {}

    public function index(Chapter $chapter): JsonResponse
    {
        $this->authorize('view', $chapter->book);

        $sections = $chapter->sections()->orderBy('order')->get();

        return response()->json($sections);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        $chapter = Chapter::findOrFail($validated['chapter_id']);
        $this->authorize('update', $chapter->book);

        $nextOrder = $chapter->sections()->max('order') + 1;

        $section = $this->bookService->createSection($chapter, [
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'],
            'order' => $nextOrder,
        ]);

        return response()->json($section, 201);
    }

    public function show(Section $section): JsonResponse
    {
        $this->authorize('view', $section->chapter->book);

        $section->load('chapter');

        return response()->json($section);
    }

    public function update(Request $request, Section $section): JsonResponse
    {
        $this->authorize('update', $section->chapter->book);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'sometimes|string',
            'order' => 'sometimes|integer|min:0',
        ]);

        $section->update($validated);

        if (isset($validated['content'])) {
            $section->updateWordCount();
        }

        return response()->json($section);
    }

    public function destroy(Section $section): JsonResponse
    {
        $this->authorize('delete', $section->chapter->book);

        $section->delete();

        return response()->json(null, 204);
    }

    public function reorder(Request $request, Chapter $chapter): JsonResponse
    {
        $this->authorize('update', $chapter->book);

        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:sections,id',
            'sections.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['sections'] as $item) {
            Section::where('id', $item['id'])
                ->where('chapter_id', $chapter->id)
                ->update(['order' => $item['order']]);
        }

        return response()->json(['message' => 'Sections reordered']);
    }
}
