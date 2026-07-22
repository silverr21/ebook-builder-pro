<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\AIController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\Api\ExportController;

Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Books CRUD
    Route::apiResource('books', BookController::class);
    Route::post('books/{book}/publish', [BookController::class, 'publish']);
    Route::get('books/{book}/statistics', [BookController::class, 'statistics']);

    // AI Writer
    Route::prefix('ai')->group(function () {
        Route::post('/generate-title', [AIController::class, 'generateTitle']);
        Route::post('/generate-chapter/{book}', [AIController::class, 'generateChapter']);
        Route::post('/expand-text', [AIController::class, 'expandText']);
        Route::post('/shorten-text', [AIController::class, 'shortenText']);
        Route::post('/rewrite-text', [AIController::class, 'rewriteText']);
        Route::post('/continue-writing', [AIController::class, 'continueWriting']);
        Route::post('/check-grammar', [AIController::class, 'checkGrammar']);
        Route::post('/generate-seo/{book}', [AIController::class, 'generateSEO']);
        Route::post('/generate-faq', [AIController::class, 'generateFAQ']);
        Route::post('/generate-glossary', [AIController::class, 'generateGlossary']);
    });

    // Chapters
    Route::get('books/{book}/chapters', [ChapterController::class, 'index']);
    Route::post('chapters', [ChapterController::class, 'store'])->name('api.chapters.store');
    Route::get('chapters/{chapter}', [ChapterController::class, 'show']);
    Route::put('chapters/{chapter}', [ChapterController::class, 'update']);
    Route::delete('chapters/{chapter}', [ChapterController::class, 'destroy']);
    Route::post('books/{book}/chapters/reorder', [ChapterController::class, 'reorder']);

    // Sections
    Route::get('chapters/{chapter}/sections', [SectionController::class, 'index']);
    Route::post('sections', [SectionController::class, 'store']);
    Route::get('sections/{section}', [SectionController::class, 'show']);
    Route::put('sections/{section}', [SectionController::class, 'update']);
    Route::delete('sections/{section}', [SectionController::class, 'destroy']);
    Route::post('chapters/{chapter}/sections/reorder', [SectionController::class, 'reorder']);

    // Design
    Route::get('design/{book}', [DesignController::class, 'show']);
    Route::post('design/apply-template/{book}', [DesignController::class, 'applyTemplate'])->name('api.design.apply-template');
    Route::put('design/{book}', [DesignController::class, 'update']);
    Route::post('design/generate-cover/{book}', [DesignController::class, 'generateCover']);
    Route::get('design/export/{book}', [DesignController::class, 'exportDesign']);
    Route::post('design/import/{book}', [DesignController::class, 'importDesign']);
    Route::post('design/reset/{book}', [DesignController::class, 'reset']);

    // Exports
    Route::prefix('export')->name('api.export.')->group(function () {
        Route::get('pdf/{book}', [ExportController::class, 'pdf'])->name('pdf');
        Route::get('epub/{book}', [ExportController::class, 'epub'])->name('epub');
        Route::get('mobi/{book}', [ExportController::class, 'mobi'])->name('mobi');
        Route::get('docx/{book}', [ExportController::class, 'docx'])->name('docx');
        Route::get('markdown/{book}', [ExportController::class, 'markdown'])->name('markdown');
        Route::get('html/{book}', [ExportController::class, 'html'])->name('html');
    });
});

// Public routes
Route::get('books/{book}/preview', [BookController::class, 'show']);
