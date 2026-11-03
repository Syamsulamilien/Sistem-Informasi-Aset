<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Borrowing;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BorrowingPhotoTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;
    protected Asset $asset;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $location = Location::create([
            'name' => 'Lab Komputer 1',
            'floor' => '1',
        ]);

        $assetType = AssetType::create([
            'name' => 'Laptop',
            'code_prefix' => 'LPT',
            'kategori' => 'Asset TI',
        ]);

        $this->user = User::create([
            'name' => 'Siswa Penguji',
            'email' => 'siswa@example.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'gender' => 'Laki-laki',
            'class_name' => 'XI RPL 1',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Laboran',
            'email' => 'admin_lab@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'gender' => 'Perempuan',
            'location_id' => $location->id,
        ]);

        $this->asset = Asset::create([
            'asset_code' => 'AST-LAB-001',
            'asset_type_id' => $assetType->id,
            'kategori' => 'Asset TI',
            'brand' => 'Lenovo',
            'model' => 'ThinkPad T480',
            'purchase_year' => 2024,
            'price' => 10000000,
            'condition' => 'Baik',
            'status' => 'Aktif',
            'location_id' => $location->id,
        ]);
    }

    public function test_can_create_borrowing_with_uploaded_photo(): void
    {
        $file = UploadedFile::fake()->image('bukti_pinjam.jpg');

        $response = $this->actingAs($this->user)->post(route('borrowings.store'), [
            'asset_id' => $this->asset->id,
            'borrow_date' => now()->toDateString(),
            'expected_return_date' => now()->addDay()->toDateString(),
            'notes' => 'Peminjaman untuk praktikum',
            'borrow_photo' => $file,
        ]);

        $response->assertRedirect(route('borrowings.index'));
        $response->assertSessionHas('success');

        $borrowing = Borrowing::where('user_id', $this->user->id)->latest()->first();
        $this->assertNotNull($borrowing);
        $this->assertNotNull($borrowing->borrow_photo);
        Storage::disk('public')->assertExists($borrowing->borrow_photo);
    }

    public function test_can_create_borrowing_with_base64_captured_photo(): void
    {
        // 1x1 transparent PNG base64
        $base64Image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $response = $this->actingAs($this->user)->post(route('borrowings.store'), [
            'asset_id' => $this->asset->id,
            'borrow_date' => now()->toDateString(),
            'expected_return_date' => now()->addDay()->toDateString(),
            'notes' => 'Foto via webcam',
            'borrow_photo_base64' => $base64Image,
        ]);

        $response->assertRedirect(route('borrowings.index'));

        $borrowing = Borrowing::where('user_id', $this->user->id)->latest()->first();
        $this->assertNotNull($borrowing);
        $this->assertEquals('borrowed', $borrowing->status);
        $this->assertNotNull($borrowing->borrow_photo);
        Storage::disk('public')->assertExists($borrowing->borrow_photo);
    }

    public function test_cannot_borrow_asset_that_is_already_borrowed(): void
    {
        // Peminjaman pertama
        Borrowing::create([
            'user_id' => $this->user->id,
            'asset_id' => $this->asset->id,
            'borrow_date' => now(),
            'expected_return_date' => now()->addDay(),
            'status' => 'borrowed',
        ]);

        // Peminjam kedua mencoba pinjam aset yang sama yang belum dikembalikan
        $user2 = User::create([
            'name' => 'Siswa Kedua',
            'email' => 'siswa2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'gender' => 'Perempuan',
        ]);

        $response = $this->actingAs($user2)->post(route('borrowings.store'), [
            'asset_id' => $this->asset->id,
            'borrow_date' => now()->toDateString(),
            'expected_return_date' => now()->addDay()->toDateString(),
            'notes' => 'Mencoba meminjam aset yang sama',
        ]);

        $response->assertSessionHasErrors('asset_id');
    }

    public function test_admin_can_update_status_and_add_return_photo(): void
    {
        $borrowing = Borrowing::create([
            'user_id' => $this->user->id,
            'asset_id' => $this->asset->id,
            'borrow_date' => now(),
            'expected_return_date' => now()->addDay(),
            'status' => 'borrowed',
        ]);

        $returnFile = UploadedFile::fake()->image('bukti_kembali.jpg');

        $response = $this->actingAs($this->admin)->patch(route('borrowings.update', $borrowing), [
            'status' => 'returned',
            'return_photo' => $returnFile,
        ]);

        $response->assertRedirect(route('borrowings.index'));

        $borrowing->refresh();
        $this->assertEquals('returned', $borrowing->status);
        $this->assertNotNull($borrowing->return_photo);
        $this->assertNotNull($borrowing->actual_return_date);
        Storage::disk('public')->assertExists($borrowing->return_photo);
    }

    public function test_deleting_borrowing_cleans_up_stored_photos(): void
    {
        $file = UploadedFile::fake()->image('pinjam.jpg');
        $storedPath = $file->store('borrowings/photos', 'public');

        $borrowing = Borrowing::create([
            'user_id' => $this->user->id,
            'asset_id' => $this->asset->id,
            'borrow_date' => now(),
            'expected_return_date' => now()->addDay(),
            'status' => 'pending',
            'borrow_photo' => $storedPath,
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'gender' => 'Laki-laki',
        ]);

        $response = $this->actingAs($superAdmin)->delete(route('borrowings.destroy', $borrowing));

        $response->assertRedirect(route('borrowings.index'));
        Storage::disk('public')->assertMissing($storedPath);
    }
}
