<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class ViewPendaftaran extends ViewRecord
{
    protected static string $view = 'filament.pages.pendaftaran.view-pendaftaran';
    protected static ?string $title = 'Detail Pendaftaran';
    protected static string $resource = PendaftaranResource::class;
    protected function getHeaderActions(): array
    {
        $actions = [Action::make('kembali')
            ->label('Kembali')
            ->url(route('filament.user.resources.pendaftarans.index'))
            ->color('gray'),];
        // Tombol untuk peserta hanya bisa edit ketika disuruh revisi
        if (auth()->user()->role === 'peserta' && auth()->id() === $this->record->user_id) {
            $actions[] = Actions\EditAction::make()
                ->visible(fn() => in_array($this->record->status_pendaftaran, ['revisi']));
        }

        // Tombol untuk admin
        if (auth()->user()->role === 'admin') {
            $actions[] = Actions\Action::make('print_reply_letter')
                ->label('Cetak Surat Balasan')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->visible(fn() => $this->record->status_pendaftaran === 'diterima')
                ->url(fn(): string => route('print.surat.balasan', ['pendaftaran' => $this->record->id]))
                ->openUrlInNewTab();
            // Tombol Verifikasi untuk admin
            $actions[] = Actions\Action::make('verifikasi')
                ->label('Verifikasi')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn() => $this->record->status_pendaftaran === 'menunggu_verifikasi' || $this->record->status_pendaftaran === 'revisi')
                ->action(function () {
                    $this->record->update([
                        'status_pendaftaran' => 'menunggu_keputusan',
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Pendaftaran Diverifikasi')
                        ->body('Pendaftaran telah diverifikasi dan menunggu keputusan Kepala Dinas.')
                        ->send();
                });

            // Tombol Revisi untuk admin
            $actions[] = Actions\Action::make('revisi')
                ->label('Minta Revisi')
                ->color('warning')
                ->icon('heroicon-o-pencil')
                ->visible(fn() => in_array($this->record->status_pendaftaran, ['menunggu_verifikasi', 'revisi']))
                ->form([
                    Textarea::make('komentar')
                        ->label('Komentar Revisi')
                        ->required()
                        ->maxLength(255)
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status_pendaftaran' => 'revisi',
                        'komentar' => $data['komentar'],
                    ]);

                    Notification::make()
                        ->warning()
                        ->title('Revisi Diminta')
                        ->body('Permintaan revisi telah dikirim ke peserta.')
                        ->send();
                });
        }

        // Tombol untuk kadin
        if (auth()->user()->role === 'kadin') {
            // Tombol Terima untuk kadin
            $actions[] = Actions\Action::make('terima')
                ->label('Terima Magang')
                ->color('success')
                ->icon('heroicon-o-check')
                ->visible(fn() => $this->record->status_pendaftaran === 'menunggu_keputusan')
                ->action(function () {
                    $this->record->update([
                        'status_pendaftaran' => 'diterima',
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Pendaftaran Diterima')
                        ->body('Pendaftaran magang telah diterima.')
                        ->send();
                });

            // Tombol Tolak untuk kadin
            $actions[] = Actions\Action::make('tolak')
                ->label('Tolak Magang')
                ->color('danger')
                ->icon('heroicon-o-x-mark')
                ->visible(fn() => $this->record->status_pendaftaran === 'menunggu_keputusan')
                ->form([
                    Textarea::make('komentar')
                        ->label('Alasan Penolakan')
                        ->maxLength(255)
                        ->required()
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status_pendaftaran' => 'ditolak',
                        'komentar' => $data['komentar'],
                    ]);

                    Notification::make()
                        ->danger()
                        ->title('Pendaftaran Ditolak')
                        ->body('Pendaftaran magang telah ditolak.')
                        ->send();
                });
        }

        return $actions;
    }

    public function getUser(): \App\Models\User
    {
        return $this->record->user;
    }
}
