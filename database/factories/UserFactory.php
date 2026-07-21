<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            // Defaults to super_admin (not the users.role column default of
            // sub_admin) so every pre-existing test that does
            // User::factory()->create() + actingAs() keeps working exactly
            // as before RBAC existed — super admins bypass all permission
            // checks. Tests that specifically need a restricted admin should
            // use the subAdmin() state below.
            'role' => 'super_admin',
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that this admin is a restricted sub-admin (no permissions by default).
     */
    public function subAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'sub_admin',
        ]);
    }

    /**
     * Indicate that this admin's account is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }
}
