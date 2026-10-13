<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class SecurityRedirectTest extends TestCase
{
    public function test_guest_unknown_endpoint_redirects_to_login(): void
    {
        $response = $this->get('/non-existent-page-12345');
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_unknown_endpoint_redirects_to_dashboard(): void
    {
        $user = new User();
        $user->id = 3;
        $user->name = 'Viewer';
        $user->role = 'viewer';

        $response = $this->actingAs($user)->get('/non-existent-page-12345');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_viewer_accessing_admin_endpoint_redirects_to_dashboard_with_error(): void
    {
        $user = new User();
        $user->id = 3;
        $user->name = 'Viewer';
        $user->role = 'viewer';

        // Viewer mencoba akses create aset (khusus admin)
        $response = $this->actingAs($user)->get('/assets/create');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }
}
