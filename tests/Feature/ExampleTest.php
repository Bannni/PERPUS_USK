<?php

namespace Tests\Feature;

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
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
        $response->assertDontSee(route('logout'));
    }

    public function test_landing_page_shows_logout_for_authenticated_user_and_can_logout(): void
    {
        $user = \App\Models\User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('logout'));
        $response->assertSee('Keluar');
        $response->assertSee(route('catalog'));
        $response->assertSee(route('orders.index'));

        // Post logout
        $logoutResponse = $this->post(route('logout'));
        $logoutResponse->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
