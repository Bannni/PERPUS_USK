<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBoundaryBarrierTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin_test@readowl.com',
        ]);
        
        $this->user = User::factory()->create([
            'role' => 'user',
            'email' => 'user_test@readowl.com',
        ]);
    }

    /**
     * Test that Admin CANNOT access user routes and is redirected to admin.dashboard.
     */
    public function test_admin_is_strictly_blocked_from_user_routes()
    {
        $admin = $this->admin;

        // Admin visiting /catalog must be redirected to /admin
        $response = $this->actingAs($admin)->get('/catalog');
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // Admin visiting /library must be redirected to /admin
        $response = $this->actingAs($admin)->get('/library');
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // Admin visiting /shop must be redirected to /admin
        $response = $this->actingAs($admin)->get('/shop');
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // Admin visiting /cart must be redirected to /admin
        $response = $this->actingAs($admin)->get('/cart');
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // Admin visiting /my-orders must be redirected to /admin
        $response = $this->actingAs($admin)->get('/my-orders');
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // Admin visiting / must be redirected to /admin
        $response = $this->actingAs($admin)->get('/');
        $response->assertRedirect(route('admin.dashboard'));

        // Admin visiting /about must be redirected to /admin
        $response = $this->actingAs($admin)->get('/about');
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // Admin visiting /contact must be redirected to /admin
        $response = $this->actingAs($admin)->get('/contact');
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
    }

    /**
     * Test that Regular User CANNOT access admin routes and is redirected to /catalog.
     */
    public function test_user_is_strictly_blocked_from_admin_routes()
    {
        $user = $this->user;

        // User visiting /admin must be redirected to /catalog
        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect(route('catalog'));
        $response->assertSessionHas('error');

        // User visiting /admin/books must be redirected to /catalog
        $response = $this->actingAs($user)->get('/admin/books');
        $response->assertRedirect(route('catalog'));
        $response->assertSessionHas('error');

        // User visiting /admin/categories must be redirected to /catalog
        $response = $this->actingAs($user)->get('/admin/categories');
        $response->assertRedirect(route('catalog'));
        $response->assertSessionHas('error');

        // User visiting /admin/orders must be redirected to /catalog
        $response = $this->actingAs($user)->get('/admin/orders');
        $response->assertRedirect(route('catalog'));
        $response->assertSessionHas('error');

        // User visiting /admin/users must be redirected to /catalog
        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertRedirect(route('catalog'));
        $response->assertSessionHas('error');
    }

    /**
     * Test that Regular User CAN access all reader routes normally.
     */
    public function test_user_can_access_user_routes_normally()
    {
        $user = $this->user;

        $response = $this->actingAs($user)->get('/catalog');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/shop');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/cart');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/my-orders');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/about');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/contact');
        $response->assertStatus(200);
    }

    /**
     * Test that Admin CAN access admin dashboard and resources normally.
     */
    public function test_admin_can_access_admin_dashboard_normally()
    {
        $admin = $this->admin;

        // Admin dashboard loads with status 200
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);

        // Admin unified tabs load with status 200
        $response = $this->actingAs($admin)->get('/admin?tab=books');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin?tab=categories');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin?tab=orders');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin?tab=users');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin?tab=contacts');
        $response->assertStatus(200);

        // Resource aliases redirect to dashboard tabs (Unified Single-Dashboard Architecture)
        $response = $this->actingAs($admin)->get('/admin/orders');
        $response->assertRedirect(route('admin.dashboard', ['tab' => 'orders']));

        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertRedirect(route('admin.dashboard', ['tab' => 'users']));
    }
}
