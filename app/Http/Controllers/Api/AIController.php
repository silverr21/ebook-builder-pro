<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Section;
use App\Services\AI\AIWriterService;
use App\Services\Book\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIController extends Controller
{
    public function __construct(
        protected AIWriterService $aiWriter,
        protected BookService $bookService,
    ) {
        // Use authenticated user's API key for all AI operations
        if (auth()->user()) {
            $this->aiWriter->setUser(auth()->user());
        }
    }

    public function generateTitle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic' => 'required|string',
            'genre' => 'required|string',
        ]);

        try {
            $title = $this->aiWriter->generateTitle($validated['topic'], $validated['genre']);
            return response()->json(['title' => $title]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function generateChapter(Request $request, Book $book): JsonResponse
    {
        $this->authorize('update', $book);
        
        $validated = $request->validate([
            'title' => 'required|string',
            'topic' => 'required|string',
            'word_count' => 'integer|min:500|max:5000',
        ]);

        try {
            $content = $this->aiWriter->generateChapter(
                $book->title,
                $validated['topic'],
                $validated['word_count'] ?? 2000
            );

            $chapter = $this->bookService->createChapter($book, [
                'title' => $validated['title'],
                'description' => $validated['topic'],
            ]);

            $this->bookService->createSection($chapter, [
                'title' => null,
                'content' => $content,
            ]);

            return response()->json(['chapter' => $chapter->load('sections')], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function expandText(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'ratio' => 'numeric|min:1.1|max:3',
        ]);

        try {
            $expanded = $this->aiWriter->expandText($validated['text'], $validated['ratio'] ?? 1.5);
            return response()->json(['expanded' => $expanded]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function shortenText(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'ratio' => 'numeric|min:0.3|max:0.9',
        ]);

        try {
            $shortened = $this->aiWriter->shortenText($validated['text'], $validated['ratio'] ?? 0.7);
            return response()->json(['shortened' => $shortened]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function rewriteText(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'style' => 'string|in:formal,casual,academic,poetic,technical',
        ]);

        try {
            $rewritten = $this->aiWriter->rewriteText($validated['text'], $validated['style'] ?? 'formal');
            return response()->json(['rewritten' => $rewritten]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function continueWriting(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'words' => 'integer|min:100|max:2000',
        ]);

        try {
            $continued = $this->aiWriter->continueWriting($validated['text'], $validated['words'] ?? 500);
            return response()->json(['continued' => $continued]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function checkGrammar(Request $request): JsonResponse
    {
        $validated = $request->validate(['text' => 'required|string']);

        try {
            $issues = $this->aiWriter->checkGrammar($validated['text']);
            return response()->json(['issues' => $issues]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function generateSEO(Request $request, Book $book): JsonResponse
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
        ]);

        try {
            $seo = $this->aiWriter->generateSEO($book->title, $validated['description'] ?? $book->description);
            return response()->json($seo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function generateFAQ(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic' => 'required|string',
            'questions' => 'integer|min:5|max:50',
        ]);

        try {
            $faq = $this->aiWriter->generateFAQ($validated['topic'], $validated['questions'] ?? 10);
            return response()->json($faq);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function generateSummary(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'sentence_count' => 'integer|min:1|max:20',
        ]);

        try {
            $summary = $this->aiWriter->generateSummary($validated['text'], $validated['sentence_count'] ?? 5);
            return response()->json(['summary' => $summary]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function generateGlossary(Request $request): JsonResponse
    {
        $validated = $request->validate(['text' => 'required|string']);

        try {
            $glossary = $this->aiWriter->generateGlossary($validated['text']);
            return response()->json($glossary);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function generateEntireBook(Request $request, Book $book): JsonResponse
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'topic' => 'required|string',
            'genre' => 'required|string|max:50',
            'style' => 'required|string|in:formal,casual,academic,poetic,technical',
            'chapters' => 'integer|min:1|max:20',
        ]);

        try {

            $chapters = $this->aiWriter->generateEntireBook(
                $book->title,
                $validated['topic'],
                $validated['genre'],
                $validated['style'],
                $validated['chapters'] ?? 5
            );

            $createdChapters = [];
            foreach ($chapters as $chapterData) {
                $chapter = $this->bookService->createChapter($book, [
                    'title' => $chapterData['title'] ?? 'Kapitola',
                    'description' => $validated['topic'],
                ]);

                $this->bookService->createSection($chapter, [
                    'title' => null,
                    'content' => $chapterData['content'] ?? '',
                ]);

                $createdChapters[] = $chapter->load('sections');
            }

            return response()->json([
                'message' => 'Book generated successfully',
                'chapters' => $createdChapters,
                'count' => count($createdChapters),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
