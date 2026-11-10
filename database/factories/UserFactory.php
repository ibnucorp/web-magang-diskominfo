<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {

        return [
            'role' => 'peserta',
            'nomor_pokok' => function () {
                $tahun = date('y');
                $kodeProdi = $this->faker->randomElement(['01', '02', '03', '04']);
                $nomorUrut = $this->faker->numerify('#####');
                return $tahun . $kodeProdi . $nomorUrut; // Format: YYPPNNNNN
            },
            'nama_lengkap' => $nama = "{$this->faker->firstName()} " . "{$this->faker->lastName()}",
            'jurusan' => $this->faker->randomElement(['Informatika', 'Sistem Informasi', 'Teknik Komputer']),
            'tempat_lahir' => $this->faker->city(),
            'tgl_lahir' => $this->faker->date(),
            'instansi' => $this->faker->randomElement([
                'SMAN 1 ' . $this->faker->city(),
                'SMKN ' . $this->faker->city(),
                'Universitas ' . $this->faker->city(),
                'Politeknik ' . $this->faker->city(),
                'SMA ' . $this->faker->streetName(),
                'MAN ' . $this->faker->city(),
            ]),
            'username' => $this->generateUsername($nama),
            'email' => $this->generateEmail($nama),
            'telpon' => $this->faker->phoneNumber(),
            'password' => Hash::make('password'), // default password
        ];
    }

    protected function generateUsername(string $nama): string
    {
        $namaParts = explode(' ', Str::lower($nama));
        $username = $namaParts[0]; // Nama depan

        // Jika ada nama belakang, tambah inisial
        if (count($namaParts) > 1) {
            $username .= substr($namaParts[1], 0, 1); // Inisial nama belakang
        }

        // Tambah angka acak
        $username .= $this->faker->randomNumber(2, false);

        return Str::slug($username, '');
    }

    protected function generateEmail(string $nama): string
    {
        $domain = $this->faker->randomElement(['gmail.com', 'yahoo.com', 'student.ac.id']);
        $emailUsername = Str::lower(str_replace(' ', '.', $nama));

        return "{$emailUsername}@{$domain}";
    }
}
