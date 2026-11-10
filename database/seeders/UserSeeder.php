<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'role' => 'admin',
            'nomor_pokok' => 'ADMIN001',
            'nama_lengkap' => 'Admin Kominfo',
            'jurusan' => 'Sistem Informasi',
            'tempat_lahir' => 'Palembang',
            'tgl_lahir' => '1990-01-01',
            'instansi' => 'Diskominfo Palembang',
            'username' => 'admin',
            'email' => 'admin@kominfo.go.id',
            'telpon' => '081234567890',
            'password' => Hash::make('admin'),
        ]);

        // Kepala Dinas
        User::create([
            'role' => 'kadin',
            'nomor_pokok' => 'KADIN001',
            'nama_lengkap' => 'Kepala Dinas',
            'jurusan' => 'Teknik Informatika',
            'tempat_lahir' => 'Palembang',
            'tgl_lahir' => '1980-01-01',
            'instansi' => 'Diskominfo Palembang',
            'username' => 'kadin',
            'email' => 'kadin@kominfo.go.id',
            'telpon' => '081234567891',
            'password' => Hash::make('kadin'),
        ]);

        // Peserta
        User::create([
            'role' => 'peserta',
            'nomor_pokok' => '011',
            'nama_lengkap' => 'Aditya',
            'jurusan' => 'Informatika',
            'tempat_lahir' => 'Bogor',
            'tgl_lahir' => '2002-01-09',
            'instansi' => 'Universitas',
            'username' => 'aditya',
            'email' => 'aditya@mail.com',
            'telpon' => '081234567892',
            'password' => Hash::make('aditya'),
        ]);
    }
}
