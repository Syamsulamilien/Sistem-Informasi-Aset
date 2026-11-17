<?php

namespace Tests\Feature;

use App\Models\Consumable;
use App\Models\ConsumableRequest;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ConsumableRequestTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;
    protected Location $location;
    protected Consumable $consumable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->location = Location::create([
            'name' => 'Lab Farmasi',
            'code' => 'LF',
            'building' => 'Gedung A',
            'floor' => '2',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Farmasi',
            'email' => 'adminfarmasi@smkbinatama.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'gender' => 'Laki-laki',
            'location_id' => $this->location->id,
        ]);

        $this->user = User::create([
            'name' => 'Siswa Farmasi',
            'email' => 'siswafarmasi@smkbinatama.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'gender' => 'Perempuan',
            'class_name' => 'X Farmasi 1',
        ]);

        $this->consumable = Consumable::create([
            'name' => 'Alkohol 70% 100ml',
            'kategori' => 'Reagen & Cairan',
            'sumber_dana' => 'Dana BOSDA',
            'stock' => 50,
            'unit' => 'botol',
            'location_id' => $this->location->id,
        ]);
    }

    public function test_user_can_submit_consumable_request(): void
    {
        $response = $this->actingAs($this->user)->post(route('consumables.request.store', $this->consumable), [
            'quantity' => 5,
            'request_date' => now()->toDateString(),
            'notes' => 'Praktikum ekstraksi',
        ]);

        $response->assertSessionHas('success');

        $req = ConsumableRequest::where('user_id', $this->user->id)->first();
        $this->assertNotNull($req);
        $this->assertEquals(5, $req->quantity);
        $this->assertEquals('pending', $req->status);
    }

    public function test_room_admin_can_approve_consumable_request_and_deduct_stock(): void
    {
        $req = ConsumableRequest::create([
            'consumable_id' => $this->consumable->id,
            'user_id' => $this->user->id,
            'quantity' => 10,
            'request_date' => now(),
            'notes' => 'Keperluan praktikum',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('consumables.request.approve', $req), [
            'notes' => 'Disetujui untuk lab',
        ]);

        $response->assertSessionHas('success');

        $req->refresh();
        $this->assertEquals('approved', $req->status);
        $this->assertEquals($this->admin->id, $req->approved_by);

        // Stock was 50, deducted by 10 -> should be 40
        $this->consumable->refresh();
        $this->assertEquals(40, $this->consumable->stock);
    }

    public function test_room_admin_can_reject_consumable_request(): void
    {
        $req = ConsumableRequest::create([
            'consumable_id' => $this->consumable->id,
            'user_id' => $this->user->id,
            'quantity' => 10,
            'request_date' => now(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('consumables.request.reject', $req), [
            'reason' => 'Stok dialokasikan untuk UKK',
        ]);

        $response->assertSessionHas('success');

        $req->refresh();
        $this->assertEquals('rejected', $req->status);
        $this->assertEquals($this->admin->id, $req->approved_by);
        $this->assertEquals('Stok dialokasikan untuk UKK', $req->approval_notes);

        // Stock remains unchanged
        $this->consumable->refresh();
        $this->assertEquals(50, $this->consumable->stock);
    }
}
