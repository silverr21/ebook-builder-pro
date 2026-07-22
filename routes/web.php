<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\BooksController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Books
    Route::get('/books', [BooksController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BooksController::class, 'show'])->name('books.show');
    Route::post('/books', [BooksController::class, 'store'])->name('books.store');

    // Editor
    Route::get('/editor/new', [EditorController::class, 'create'])->name('editor.new');
    Route::get('/editor/{book}', [EditorController::class, 'show'])->name('editor.show');
    Route::get('/editor/{book}/design', [EditorController::class, 'design'])->name('editor.design');
    Route::get('/editor/{book}/preview', [EditorController::class, 'preview'])->name('editor.preview');
    Route::get('/editor/{book}/export', [EditorController::class, 'export'])->name('editor.export');
    Route::get('/editor/{book}/settings', [EditorController::class, 'settings'])->name('editor.settings');

    // AI
    Route::get('/ai', [EditorController::class, 'aiExplore'])->name('ai.explore');

    // Templates
    Route::get('/templates', [EditorController::class, 'templates'])->name('templates');
});
