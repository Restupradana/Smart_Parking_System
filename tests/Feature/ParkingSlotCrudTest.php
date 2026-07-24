<?php

use App\Models\ParkingArea;
use App\Models\User;

test('admin can view parking slots list', function () {
    $user = User::factory()->admin()->create();
    $area = ParkingArea::create(['name' => 'Area A', 'location' => 'Gedung Utama']);

    $response = $this->actingAs($user)->get(route('admin.parking-slots.index'));
    $response->assertStatus(200);
});

test('admin can create parking slot', function () {
    $user = User::factory()->admin()->create();
    $area = ParkingArea::create(['name' => 'Area A', 'location' => 'Gedung Utama']);

    $response = $this->actingAs($user)->post(route('admin.parking-slots.store'), [
        'area_id' => $area->id,
        'slot_number' => 'A-1',
        'status' => 'kosong',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('parking_slots', [
        'slot_number' => 'A-1',
        'area_id' => $area->id,
    ]);
});
