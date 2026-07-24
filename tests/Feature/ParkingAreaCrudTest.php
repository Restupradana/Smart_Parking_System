<?php

use App\Models\User;
use App\Models\ParkingArea;

test('admin can view parking areas list', function () {
    $user = User::factory()->admin()->create();
    ParkingArea::create(['name' => 'Area A', 'location' => 'Gedung Utama']);

    $response = $this->actingAs($user)->get(route('admin.parking-areas.index'));
    $response->assertStatus(200);
    $response->assertSee('Area A');
});

test('admin can create parking area', function () {
    $user = User::factory()->admin()->create();

    $response = $this->actingAs($user)->post(route('admin.parking-areas.store'), [
        'name' => 'Area B',
        'location' => 'Gedung Barat',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('parking_areas', [
        'name' => 'Area B',
        'location' => 'Gedung Barat',
    ]);
});

test('admin can update parking area', function () {
    $user = User::factory()->admin()->create();
    $area = ParkingArea::create(['name' => 'Area A', 'location' => 'Gedung Utama']);

    $response = $this->actingAs($user)->put(route('admin.parking-areas.update', $area), [
        'name' => 'Area A Updated',
        'location' => 'Gedung Utama Lantai 2',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('parking_areas', [
        'id' => $area->id,
        'name' => 'Area A Updated',
    ]);
});
