<?php

use App\Models\User;

test('admin is redirected to admin dashboard', function () {
    $user = User::factory()->admin()->create();
    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(302)->assertRedirect(route('admin.dashboard'));
});

test('non-admin user is redirected to their own dashboard', function () {
    $user = User::factory()->pengguna()->create();
    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(302)->assertRedirect(route('pengguna.dashboard'));
});

test('unauthenticated user is redirected to login', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});
