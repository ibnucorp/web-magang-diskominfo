<?php

namespace App\Filament\Pages\Pendaftaran;

use Filament\Pages\Page;

class ViewPendaftaran extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.pendaftaran.view-pendaftaran';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return false;
    }
}
