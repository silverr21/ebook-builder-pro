<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditorFocusModeTest extends TestCase
{
    use RefreshDatabase;

    public function test_normal_editor_shows_focus_toggle_and_open_panels(): void
    {
        $user = User::factory()->create();
        $book = Book::create([
            'user_id' => $user->id,
            'title' => 'Test Book',
            'slug' => 'test-book',
        ]);

        $response = $this->actingAs($user)->get(route('editor.show', $book));

        $response->assertOk();
        $response->assertSee('Focus režim', false);
        $response->assertSee('leftPanelOpen: true', false);
        $response->assertSee('rightPanelOpen: true', false);
        $response->assertSee('sidebarOpen: true', false);
    }

    public function test_focus_editor_hides_panels_and_shows_return_toggle(): void
    {
        $user = User::factory()->create();
        $book = Book::create([
            'user_id' => $user->id,
            'title' => 'Test Book',
            'slug' => 'test-book',
        ]);

        $response = $this->actingAs($user)->get(route('editor.focus', $book));

        $response->assertOk();
        $response->assertSee('Bežný režim', false);
        $response->assertSee('leftPanelOpen: false', false);
        $response->assertSee('rightPanelOpen: false', false);
        $response->assertSee('sidebarOpen: false', false);
    }

    public function test_focus_editor_requires_authorization(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $book = Book::create([
            'user_id' => $owner->id,
            'title' => 'Private Book',
            'slug' => 'private-book',
        ]);

        $response = $this->actingAs($stranger)->get(route('editor.focus', $book));

        $response->assertForbidden();
    }
}
