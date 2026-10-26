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

        // Viewer mencoba akses create aset (khusus super_admin/admin)
        $response = $this->actingAs($user)->get('/assets/create');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_admin_accessing_user_management_endpoint_redirects_to_dashboard_with_error(): void
    {
        $user = new User();
        $user->id = 10;
        $user->name = 'Admin Operasional';
        $user->role = 'admin';

        // Admin biasa tidak boleh mengakses kelola user
        $response = $this->actingAs($user)->get('/users/create');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_super_admin_can_access_user_management(): void
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'Super Administrator';
        $user->role = 'super_admin';

        // Super Admin boleh mengakses endpoint kelola user
        $response = $this->actingAs($user)->get('/users/create');
        $response->assertStatus(200);
    }

    public function test_super_admin_and_admin_model_methods_and_policies(): void
    {
        $superAdmin = new User(['name' => 'Super', 'role' => 'super_admin']);
        $admin = new User(['name' => 'Admin', 'role' => 'admin']);
        $viewer = new User(['name' => 'Viewer', 'role' => 'viewer']);

        $this->assertTrue($superAdmin->isSuperAdmin());
        $this->assertTrue($superAdmin->isAdmin());

        $this->assertFalse($admin->isSuperAdmin());
        $this->assertTrue($admin->isAdmin());

        $this->assertFalse($viewer->isSuperAdmin());
        $this->assertFalse($viewer->isAdmin());

        $assetPolicy = new \App\Policies\AssetPolicy();
        $this->assertTrue($assetPolicy->create($superAdmin));
        $this->assertTrue($assetPolicy->create($admin));
        $this->assertFalse($assetPolicy->create($viewer));

        $locationPolicy = new \App\Policies\LocationPolicy();
        $this->assertTrue($locationPolicy->create($superAdmin));
        $this->assertTrue($locationPolicy->create($admin));
        $this->assertFalse($locationPolicy->create($viewer));

        $assetTypePolicy = new \App\Policies\AssetTypePolicy();
        $this->assertTrue($assetTypePolicy->create($superAdmin));
        $this->assertTrue($assetTypePolicy->create($admin));
        $this->assertFalse($assetTypePolicy->create($viewer));
    }

    public function test_user_creation_and_authentication(): void
    {
        $plainPassword = 'Password123!';
        $user = new User([
            'name' => 'Test User',
            'username' => 'testuser99',
            'email' => 'testuser99@smkbinatama.sch.id',
            'password' => \Illuminate\Support\Facades\Hash::make($plainPassword),
            'role' => 'admin',
        ]);

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check($plainPassword, $user->password), 'Password hash check with Hash::make');

        $user2 = new User([
            'name' => 'Test User 2',
            'username' => 'testuser100',
            'email' => 'testuser100@smkbinatama.sch.id',
            'password' => $plainPassword,
            'role' => 'admin',
        ]);

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check($plainPassword, $user2->password), 'Password hash check with plain password');
    }

    public function test_pimpinan_role_permissions_and_redirects(): void
    {
        $pimpinan = new User([
            'name' => 'Kepala Sekolah',
            'username' => 'kepsek',
            'role' => 'pimpinan',
        ]);
        $pimpinan->id = 15;

        $this->assertTrue($pimpinan->isPimpinan());
        $this->assertFalse($pimpinan->isAdmin());
        $this->assertFalse($pimpinan->isSuperAdmin());

        // Policy Borrowing::create diizinkan untuk Pimpinan
        $borrowingPolicy = new \App\Policies\BorrowingPolicy();
        $this->assertTrue($borrowingPolicy->create($pimpinan));
        // Pimpinan tidak boleh approve/update status orang lain
        $this->assertFalse($borrowingPolicy->update($pimpinan, new \App\Models\Borrowing()));

        // Dashboard diarahkan langsung ke borrowings.index
        $response = $this->actingAs($pimpinan)->get('/dashboard');
        $response->assertRedirect(route('borrowings.index'));

        // Modul non-peminjaman & non-laporan diblokir
        $response = $this->actingAs($pimpinan)->get('/assets');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');

        $response = $this->actingAs($pimpinan)->get('/maintenance');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');

        $response = $this->actingAs($pimpinan)->get('/consumables');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_guru_and_siswa_role_permissions_and_redirects(): void
    {
        $guru = new User([
            'name' => 'Budi Guru',
            'username' => 'budiguru',
            'role' => 'guru',
        ]);
        $guru->id = 21;

        $siswa = new User([
            'name' => 'Ahmad Siswa',
            'username' => 'ahmadsiswa',
            'role' => 'siswa',
        ]);
        $siswa->id = 22;

        $this->assertTrue($guru->isGuru());
        $this->assertTrue($guru->isUser());
        $this->assertFalse($guru->isSiswa());

        $this->assertTrue($siswa->isSiswa());
        $this->assertTrue($siswa->isUser());
        $this->assertFalse($siswa->isGuru());

        $borrowingPolicy = new \App\Policies\BorrowingPolicy();
        // Policy Borrowing::create diizinkan untuk Guru & Siswa
        $this->assertTrue($borrowingPolicy->create($guru));
        $this->assertTrue($borrowingPolicy->create($siswa));

        // Guru & Siswa tidak boleh update status peminjaman
        $this->assertFalse($borrowingPolicy->update($guru, new \App\Models\Borrowing()));
        $this->assertFalse($borrowingPolicy->update($siswa, new \App\Models\Borrowing()));

        // Dashboard diarahkan langsung ke borrowings.index
        $response = $this->actingAs($guru)->get('/dashboard');
        $response->assertRedirect(route('borrowings.index'));

        $response = $this->actingAs($siswa)->get('/dashboard');
        $response->assertRedirect(route('borrowings.index'));

        // Modul laporan diblokir untuk guru & siswa
        $response = $this->actingAs($guru)->get('/reports');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');

        $response = $this->actingAs($siswa)->get('/reports');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');

        // Modul aset diblokir
        $response = $this->actingAs($guru)->get('/assets');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');

        $response = $this->actingAs($siswa)->get('/assets');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }
}
