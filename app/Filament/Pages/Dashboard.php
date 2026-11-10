<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Models\Pendaftaran;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;

class Dashboard extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $slug = 'dashboard';
    protected static ?int $navigationSort = -1;

    public static function getWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
    public function table(Table $table): Table
    {
        $query = $this->getTableQueryForRole();
        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('user.nama_lengkap')
                    ->label('Nama Peserta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pendaftaran')
                    ->date(),
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->label('Mulai Magang')
                    ->date(),
                Tables\Columns\TextColumn::make('tgl_selesai')
                    ->label('Selesai Magang')
                    ->date(),
                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->badge()
                    ->colors([
                        'warning' => 'menunggu_verifikasi',
                        'danger' => 'revisi',
                        'primary' => 'menunggu_keputusan',
                        'success' => 'diterima',
                        'danger' => 'ditolak',
                    ]),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn(Pendaftaran $record): string => route('filament.user.resources.pendaftarans.view', ['record' => $record])),
            ])
            ->defaultSort('updated_at', 'desc')
            ->heading($this->getTableHeading());
    }

    protected function getTableQueryVerifikasi(): Builder
    {
        return Pendaftaran::query()
            ->whereIn('status_pendaftaran', ['menunggu_verifikasi', 'revisi', 'diterima', 'ditolak']);
    }

    protected function getTableQueryHasil(): Builder
    {
        return Pendaftaran::query()
            ->whereIn('status_pendaftaran', ['diterima', 'ditolak']);
    }

    protected function getTableQueryForRole(): Builder
    {
        if (auth()->user()->role === 'admin') {
            return $this->getTableQueryVerifikasi();
        }
        return Pendaftaran::query()->where('status_pendaftaran', 'menunggu_keputusan');
    }


    protected function getTableHeading(): string
    {
        if (auth()->user()->role === 'admin') {
            return 'Pendaftaran Yang Perlu Diverifikasi';
        } elseif (auth()->user()->role === 'kadin') {
            return 'Pendaftaran Yang Menunggu Keputusan';
        } else {
            return 'Pendaftaran Magang Saya';
        }
    }
}
