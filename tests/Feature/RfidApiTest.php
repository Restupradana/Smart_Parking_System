<?php

use App\Models\User;
use App\Models\ParkingSlot;
use App\Models\RfidCard;
use App\Models\RfidLog;

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
