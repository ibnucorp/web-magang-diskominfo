<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftaran;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $heading = 'Informasi Pendaftaran';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pendaftar', $this->totalPendaftar())
                ->description('Jumlah total pendaftar')
                ->color('primary')
                ->descriptionIcon('heroicon-o-users', IconPosition::Before),

            Stat::make('Dalam Proses', $this->dalamProses())
                ->description('Sedang dalam proses')
                ->color('warning')
                ->descriptionIcon('heroicon-o-clock', IconPosition::Before),

            Stat::make('Diterima', $this->diterima())
                ->description('Telah diterima')
                ->color('success')
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::Before),

            Stat::make('Ditolak', $this->ditolak())
                ->description('Tidak memenuhi syarat')
                ->color('danger')
                ->descriptionIcon('heroicon-o-x-circle', IconPosition::Before),

        ];
    }

    protected function totalPendaftar(): string
    {
        // Ganti dengan query atau data Anda
        return Pendaftaran::count();
    }

    protected function dalamProses(): string
    {
        return Pendaftaran::whereIn('status_pendaftaran', [
            'menunggu_verifikasi',
            'revisi',
            'menunggu_keputusan'
        ])->count();
    }

    protected function diterima(): string
    {
        return Pendaftaran::where('status_pendaftaran', 'diterima')->count();
    }

    protected function ditolak(): string
    {
        return Pendaftaran::where('status_pendaftaran', 'ditolak')->count();
    }
}
