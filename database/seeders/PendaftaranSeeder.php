<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pendaftaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class PendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Meratakan status
        $statuses = [
            'menunggu_verifikasi',
            'revisi',
            'menunggu_keputusan',
            'diterima',
            'ditolak',
            'menunggu_verifikasi',
            'revisi',
            'menunggu_keputusan',
            'diterima',
            'ditolak',
        ];
        // Mengacak status
        shuffle($statuses);

        // Membuat 10 user dengan pendaftarannya
        User::factory()
            ->count(10)
            ->create(['role' => 'peserta'])
            ->each(function ($user, $index) use ($statuses) {
                $status = $statuses[$index];
                Pendaftaran::factory()->create([
                    'user_id' => $user->id,
                    'status_pendaftaran' => $status,
                    'surat_pengantar' => function () use ($user) {
                        $file = UploadedFile::fake()->create('surat_pengantar.pdf');
                        // Gunakan format nama file yang sama seperti di form
                        $timestamp = now()->format('Ymd');
                        // Gunakan user dummy untuk testing
                        $namaLengkap = str_replace(' ', '_', strtolower($user->nama_lengkap));
                        $extension = $file->getClientOriginalExtension();
                        $customFilename = "{$timestamp}_{$namaLengkap}.{$extension}";

                        return $file->storeAs('surat_pengantar', $customFilename, 'public');
                    },
                ]);
            });
    }
}
