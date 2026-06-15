<?php

namespace Tests\Feature;

use Database\Seeders\PagesSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        (new SettingsSeeder())->run();
        (new PagesSeeder())->run();

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
