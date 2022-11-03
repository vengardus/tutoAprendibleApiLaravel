<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;


class BookApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_books() {
        $books = Book::factory(4)->create();
        
        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
        //dd($book);
    }

    /** @test */
    function can_get_one_books() {
        $book = Book::factory()->create();
        
        $response = $this->getJson(route('books.show', $book));

        // $response->assertJsonFragment([
        //     'title' => $books[0]->title
        // ])->assertJsonFragment([
        //     'title' => $books[1]->title
        // ]);
        
        // dd(route('books.show', $book));

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
             'title' => $book->title
        ]);
    }

    /** @test */
    function can_create_books() {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

            $this->postJson(route('books.store'), [
            'title' => "My new book"
        ])->assertJsonFragment([
            'title' => "My new book"
        ]);

        $this->assertDatabaseHas('books', [
            'title' => "My new book"
        ]);
    }

    /** @test */
    function can_update_books() {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => "Edited book"
        ])->assertJsonFragment([
            'title' => "Edited book"
        ]);

        $this->assertDatabaseHas('books', [
            'title' => "Edited book"
        ]);
    }

    /** @test */
    function can_delete_books() {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();    

        $this->assertDatabaseCount('books', 0);
    }
}
