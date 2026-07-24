<?php

use App\Models\User;

test('admin can view user management list', function () {
    $user = User::factory()->admin()->create();

    $response = $this->actingAs($user)->get(route('admin.users.index'));
    $response->assertStatus(200);
});

test('admin can create user', function () {
    $user = User::factory()->admin()->create();
    $role = \App\Models\Role::firstOrCreate(['name' => 'pengguna']);

    $response = $this->actingAs($user)->post(route('admin.users.store'), [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'rfid_tag' => 'RFID1234',
        'role_id' => $role->id,
        'is_active' => true,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
    ]);
});
