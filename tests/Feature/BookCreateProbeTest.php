<?php
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCreateProbeTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_book_via_web_form(): void
    {
        $user = User::factory()->create(['role' => 'author', 'email_verified_at' => now()]);

        $response = $this->actingAs($user)->post(route('books.store'), [
            'title' => 'Test Kniha',
            'description' => 'Popis',
            'author' => 'Autor',
        ]);

        $response->assertRedirect();
    }
}
