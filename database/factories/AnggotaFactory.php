<?php

namespace Database\Factories;

use App\Models\Anggota;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Factory untuk model Anggota.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anggota>
 */
class AnggotaFactory extends Factory
{
    protected $model = Anggota::class;

    protected static ?string $password;

    public function definition(): array
    {
        $tanggalLahir = fake()->dateTimeBetween('-25 years', '-18 years');

        return [
            'nim'                  => fake()->unique()->numerify('########'),
            'nama_lengkap'         => fake()->name(),
            'email'                => fake()->unique()->safeEmail(),
            'password'             => static::$password ??= Hash::make('password'),
            'tanggal_lahir'        => $tanggalLahir,
            'tempat_lahir'         => fake()->city(),
            'jenis_kelamin'        => fake()->randomElement(['L', 'P']),
            'no_hp'                => fake()->phoneNumber(),
            'alamat'               => fake()->address(),
            'program_studi'        => 'Teknik Informatika',
            'jurusan'              => 'Teknologi Informasi',
            'divisi'               => fake()->randomElement(['fotografi', 'pers_penyiaran', 'videografi', 'kominfo', 'redaksi', 'inventory']),
            'jabatan_struktural'   => 'anggota',
            'status_keanggotaan'   => 'aktif',
            'tanggal_bergabung'    => fake()->dateTimeBetween('-3 years', 'now'),
            'is_first_login'       => true,
            'email_verified_at'    => now(),
            'remember_token'       => Str::random(10),
        ];
    }

    /**
     * State: anggota pasif.
     */
    public function pasif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_keanggotaan' => 'pasif',
        ]);
    }

    /**
     * State: sudah pernah login (bukan first login).
     */
    public function sudahLogin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_first_login' => false,
        ]);
    }
}
