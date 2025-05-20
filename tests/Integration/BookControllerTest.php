<?php
// tests/Feature/BookControllerTest.php
// tests/Feature/BookControllerTest.php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user once for all tests
        $this->user = User::factory()->create();
    }

    public function test_store_book_successfully()
    {
        $payload = [
            'title' => 'Test Book',
            'publisher' => 'Test Publisher',
            'published_date' => '2023-01-01',
            'synopsis' => 'Test synopsis',
            'isbn' => '1234567890123',
            'pages' => 123,
            'cover' => 'http://example.com/cover.jpg',
            'authors' => ['Test Author'],
            'genres' => ['Test Genre'],
            'user_status' => 'reading',
            'user_page_count' => 10,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/books', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Book added successfully',
                     'book' => ['title' => 'Test Book']
                 ]);
    }

    public function test_get_book_by_isbn_local()
    {
        $this->actingAs($this->user)->postJson('/api/books', [
            'title' => 'Book Local',
            'publisher' => 'Local Publisher',
            'published_date' => '2022-01-01',
            'synopsis' => 'Local synopsis',
            'isbn' => '1111111111111',
            'pages' => 100,
            'cover' => '',
            'authors' => ['Author A'],
            'genres' => ['Genre A'],
            'user_status' => 'completed',
            'user_page_count' => 100,
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/books/isbn?q=1111111111111');
        $response->assertStatus(200)
                 ->assertJsonFragment(['isbn' => '1111111111111']);
                 echo $response->getContent();
    }

    public function test_search_books_by_title_or_isbn_google()
    {
        $response = $this->actingAs($this->user)->getJson('/api/books/search?q=harry+potter');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message', 'books', 'total', 'per_page', 'page'
                 ]);
    }
}
