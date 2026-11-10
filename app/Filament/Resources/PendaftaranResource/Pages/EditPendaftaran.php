<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendaftaran extends EditRecord
{
    protected static string $view = 'filament.pages.edit-pendaftaran-saya';
    protected static ?string $title = 'Edit Pendaftaran Saya';
    protected static string $resource = PendaftaranResource::class;
    protected function getHeaderActions(): array
    {
        $actions = [];
        return $actions;
    }
}
