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
        // return [
        //     'nama' => fake()->name(),
        //     'email' => fake()->unique()->safeEmail(),
        //     'role' => fake()->randomElement(['Admin', 'Anggota']),
        //     'password' => static::$password ??= Hash::make('password'),
        //     'remember_token' => Str::random(10),
        // ];

        $role = $this->faker->randomElement(['Admin', 'Anggota']);

        $data = [
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'role' => $role, // Gunakan role yang sudah ditentukan
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];

        if ($role === 'Anggota') {
            $data['kode_anggota'] = 'ANG' . str_pad($this->faker->unique()->randomNumber(4), 4, '0', STR_PAD_LEFT);
            $data['tanggal_lahir'] = $this->faker->date();
            $data['tempat_lahir'] = $this->faker->city();
            $data['jenis_kelamin'] = $this->faker->randomElement(['Laki-laki', 'Perempuan']);
            $data['no_hp'] = '+62' . $this->faker->randomNumber(9, true);
            $data['alamat'] = $this->faker->address();
        } else {
            $data['kode_anggota'] = null;
            $data['tanggal_lahir'] = null;
            $data['tempat_lahir'] = null;
            $data['jenis_kelamin'] = null;
            $data['no_hp'] = null;
            $data['alamat'] = null;
        }
        return $data;
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
}
