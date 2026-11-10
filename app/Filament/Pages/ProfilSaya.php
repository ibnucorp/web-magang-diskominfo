<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;

class ProfilSaya extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Pengguna';

    protected static string $view = 'filament.pages.profil-saya';
    protected static ?string $title = 'Profil Saya';
    protected static ?string $slug = 'profil-saya';
    public $user;
    public function mount()
    {
        $this->user = auth()->user();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editProfile')
                ->label('Edit Profil')
                ->icon('heroicon-o-pencil-square')
                ->color('primary')
                ->size(ActionSize::Large)
                ->url(route('filament.user.pages.edit-profil-saya')),

            Action::make('changePassword')
                ->label('Ganti Password')
                ->icon('heroicon-o-lock-closed')
                ->color('warning')
                ->size(ActionSize::Large)
                ->url(route('filament.user.pages.ganti-password')),
        ];
    }
}
