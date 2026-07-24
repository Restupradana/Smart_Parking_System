<?php

use App\Models\ParkingSlot;
use App\Models\RfidCard;
use App\Models\User;

test('rfid valid card grants access on entry', function () {
    $user = User::factory()->pengguna()->create();
    $card = RfidCard::create([
        'user_id' => $user->id,
        'uid' => 'TEST1234',
        'is_active' => true,
    ]);

    $area = \App\Models\ParkingArea::create(['name' => 'Area A', 'location' => 'Gedung']);
    ParkingSlot::create([
        'area_id' => $area->id,
        'slot_number' => 'A-1',
        'status' => 'kosong',
    ]);

    $response = $this->postJson('/api/v1/rfid', [
        'rfid_tag' => 'TEST1234',
        'gate' => 'masuk',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'action' => 'masuk',
        ]);

    $slot = ParkingSlot::where('slot_number', 'A-1')->first();
    $this->assertEquals('terisi', $slot->status);

    $this->assertDatabaseHas('parking_transactions', [
        'user_id' => $user->id,
        'status' => 'active',
    ]);
});

test('rfid exit frees slot and closes transaction', function () {
    $user = User::factory()->pengguna()->create();
    $card = RfidCard::create([
        'user_id' => $user->id,
        'uid' => 'EXIT5678',
        'is_active' => true,
    ]);

    $area = \App\Models\ParkingArea::create(['name' => 'Area B', 'location' => 'Gedung']);
    $slot = ParkingSlot::create([
        'area_id' => $area->id,
        'slot_number' => 'B-1',
        'status' => 'kosong',
    ]);

    $this->postJson('/api/v1/rfid', [
        'rfid_tag' => 'EXIT5678',
        'gate' => 'masuk',
    ]);

    $slot->refresh();
    $this->assertEquals('terisi', $slot->status);

    $response = $this->postJson('/api/v1/rfid', [
        'rfid_tag' => 'EXIT5678',
        'gate' => 'keluar',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'action' => 'keluar',
        ]);

    $slot->refresh();
    $this->assertEquals('kosong', $slot->status);

    $this->assertDatabaseHas('parking_transactions', [
        'user_id' => $user->id,
        'status' => 'completed',
    ]);
});

test('rfid entry with no available slots returns error', function () {
    $user = User::factory()->pengguna()->create();
    RfidCard::create([
        'user_id' => $user->id,
        'uid' => 'FULL9999',
        'is_active' => true,
    ]);

    $response = $this->postJson('/api/v1/rfid', [
        'rfid_tag' => 'FULL9999',
        'gate' => 'masuk',
    ]);

    $response->assertStatus(409)
        ->assertJson([
            'status' => 'error',
        ]);
});

test('rfid invalid card is rejected', function () {
    $response = $this->postJson('/api/v1/rfid', [
        'rfid_tag' => 'INVALID000',
        'gate' => 'masuk',
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'status' => 'error',
        ]);
});

test('sensor update slot status succeeds', function () {
    $area = \App\Models\ParkingArea::create(['name' => 'Area A', 'location' => 'Gedung']);
    $slot = ParkingSlot::create([
        'area_id' => $area->id,
        'slot_number' => 'Area A-1',
        'status' => 'kosong',
    ]);

    $response = $this->postJson('/api/v1/sensor', [
        'slot_number' => 'Area A-1',
        'status' => 'terisi',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
        ]);

    $slot->refresh();
    $this->assertEquals('terisi', $slot->status);
});
