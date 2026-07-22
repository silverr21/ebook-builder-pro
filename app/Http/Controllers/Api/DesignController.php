<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Design;
use App\Services\Design\DesignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function __construct(protected DesignService $designService) {}

    public function show(Book $book): JsonResponse
    {
        $this->authorize('view', $book);

        $book->load('design');

        return response()->json([
            'design' => $book->design,
            'templates' => $this->designService->getAvailableTemplates(),
        ]);
    }

    public function applyTemplate(Request $request, Book $book): JsonResponse
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'template' => 'required|string|in:' . implode(',', $this->designService->getAvailableTemplates()),
        ]);

        try {
            $design = $this->designService->applyTemplate($book, $validated['template']);

            return response()->json([
                'message' => 'Template applied',
                'design' => [
                    'typography' => $design->typography,
                    'colors' => $design->colors,
                    'spacing' => $design->spacing,
                    'layout' => $design->layout,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, Book $book): JsonResponse
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'typography' => 'nullable|array',
            'colors' => 'nullable|array',
            'spacing' => 'nullable|array',
            'layout' => 'nullable|array',
            'name' => 'nullable|string|max:255',
        ]);

        try {
            $design = $this->designService->createOrUpdateDesign($book, $validated);

            return response()->json([
                'message' => 'Design saved',
                'design' => $design,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function generateCover(Request $request, Book $book): JsonResponse
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'style' => 'nullable|string',
            'mood' => 'nullable|string',
        ]);

        try {
            $image = $this->designService->generateCoverDesign($book, $validated);

            return response()->json([
                'message' => 'Cover generated',
                'image' => $image,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function exportDesign(Book $book): JsonResponse
    {
        $this->authorize('view', $book);

        $design = $book->design;
        if (!$design) {
            return response()->json(['error' => 'No design found'], 404);
        }

        $json = $this->designService->exportDesignAsJSON($design);

        return response()->json([
            'design' => json_decode($json, true),
        ]);
    }

    public function importDesign(Request $request, Book $book): JsonResponse
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'design_json' => 'required|string|json',
        ]);

        try {
            $design = $this->designService->importDesignFromJSON($book, $validated['design_json']);

            return response()->json([
                'message' => 'Design imported',
                'design' => $design,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function reset(Book $book): JsonResponse
    {
        $this->authorize('update', $book);

        $design = $this->designService->applyTemplate($book, 'modern');

        return response()->json([
            'message' => 'Design reset to default',
            'design' => [
                'typography' => $design->typography,
                'colors' => $design->colors,
                'spacing' => $design->spacing,
                'layout' => $design->layout,
            ],
        ]);
    }
}
