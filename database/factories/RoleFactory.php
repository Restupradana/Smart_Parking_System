<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['admin', 'petugas', 'pengguna']),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
        ]);
    }

    public function petugas(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'petugas',
        ]);
    }

    public function pengguna(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'pengguna',
        ]);
    }
}
