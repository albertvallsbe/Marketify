<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function testNoProducts(): void
    {
        $response = $this->get('/');
        $response->assertSeeText('No se encontraron resultados.');
    }

    public function testStore(): void
    {
        $this
          ->post(route('products'), [
              'name' => 'Garden table',
              'description' => 'Taula de jardi de kaoba',
              'image' => 'url',
              'price' => '200,00€'
          ]);
          // ->assertRedirect('/');

        $this->assertDatabaseHas('products', [
              'name' => 'Garden table',
              'description' => 'Taula de jardi de kaoba',
              'image' => 'url',
              'price' => '200,00€'
            ]);
    }
}
