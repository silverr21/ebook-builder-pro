<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AIController;
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

    // Exports
    Route::prefix('export')->group(function () {
        Route::get('pdf/{book}', [ExportController::class, 'pdf']);
        Route::get('epub/{book}', [ExportController::class, 'epub']);
        Route::get('mobi/{book}', [ExportController::class, 'mobi']);
        Route::get('docx/{book}', [ExportController::class, 'docx']);
        Route::get('markdown/{book}', [ExportController::class, 'markdown']);
        Route::get('html/{book}', [ExportController::class, 'html']);
    });
});

// Public routes
Route::get('books/{book}/preview', [BookController::class, 'show']);
