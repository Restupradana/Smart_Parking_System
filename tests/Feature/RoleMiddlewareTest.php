<?php

use App\Models\User;

test('user with correct role is redirected to admin dashboard', function () {
    $user = User::factory()->admin()->create();
    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(302)->assertRedirect(route('admin.dashboard'));
});

test('user with correct role is redirected to petugas dashboard', function () {
    $user = User::factory()->petugas()->create();
    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(302)->assertRedirect(route('petugas.dashboard'));
});

test('unauthenticated user cannot access protected routes', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});
