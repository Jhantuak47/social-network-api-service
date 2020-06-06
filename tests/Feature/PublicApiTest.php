<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_public_api_welcome()
    {
        $response = $this->get('api/');

        $response->assertStatus(200)
            ->assertSee('Welcome to Social Network Portal: API Version 1');
    }
}
