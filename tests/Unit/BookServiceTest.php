<?php
// tsts/Unit/BookServiceTest.php
namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BookService;
use App\Models\Book;
use Mockery;

class BookServiceTest extends TestCase
{
    public function test_create_book()
    {
        // Mock the Book model
        $mockBook = Mockery::mock(Book::class);
        $mockBook->shouldReceive('create')->once()->andReturn(new Book([
            'title' => 'Mocked Book',
            'isbn' => '1234567890123',
        ]));

        // Inject the mock into the service
        $service = new BookService($mockBook);
        $result = $service->createBook([
            'title' => 'Mocked Book',
            'isbn' => '1234567890123',
        ], 1); // user_id = 1

        $this->assertEquals('Mocked Book', $result->title);
    }
}