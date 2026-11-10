<?php

namespace Database\Factories;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class PendaftaranFactory extends Factory
{
    protected $model = Pendaftaran::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('now', '+1 month');
        $end = (clone $start)->modify('+3 months');

        $status = $this->faker->randomElement([
            'menunggu_verifikasi',
            'revisi',
            'menunggu_keputusan',
            'diterima',
            'ditolak'
        ]);
        return [
            'tgl_mulai' => $start,
            'tgl_selesai' => $end,
            'komentar' => in_array($status, ['revisi', 'menunggu_keputusan', 'diterima', 'ditolak'])
                ? $this->faker->sentence()
                : null,
        ];
    }
}
