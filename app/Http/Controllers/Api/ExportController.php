<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\Export\ExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function __construct(protected ExportService $exportService) {}

    public function pdf(Book $book, Request $request): BinaryFileResponse|JsonResponse
    {
        $this->authorize('view', $book);

        try {
            $pdf = $this->exportService->exportPDF($book, $request->all());
            return $pdf->download("{$book->slug}.pdf");
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function epub(Book $book, Request $request): BinaryFileResponse|JsonResponse
    {
        $this->authorize('view', $book);

        try {
            $filename = $this->exportService->exportEPUB($book, $request->all());
            return response()->download($filename);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function mobi(Book $book, Request $request): BinaryFileResponse|JsonResponse
    {
        $this->authorize('view', $book);

        try {
            $filename = $this->exportService->exportMOBI($book, $request->all());
            return response()->download($filename);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function docx(Book $book, Request $request): BinaryFileResponse|JsonResponse
    {
        $this->authorize('view', $book);

        try {
            $filename = $this->exportService->exportDOCX($book, $request->all());
            return response()->download($filename);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function markdown(Book $book): BinaryFileResponse|JsonResponse
    {
        $this->authorize('view', $book);

        try {
            $filename = $this->exportService->exportMarkdown($book);
            return response()->download($filename);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function html(Book $book, Request $request): BinaryFileResponse|JsonResponse
    {
        $this->authorize('view', $book);

        try {
            $filename = $this->exportService->exportHTML($book, $request->all());
            return response()->download($filename);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
