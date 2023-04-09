<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRouteHome(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testRouteLogin(): void
    {
        $response = $this->get('/login');
        $response->assertSeeText('Log In');
        $response->assertStatus(200);
    }

    public function testRouteCart(): void
    {
        $response = $this->get('/cart');
        $response->assertSeeText('Cart');
        $response->assertStatus(200);
    }
}
