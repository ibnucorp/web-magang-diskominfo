<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class GantiPassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static string $view = 'filament.pages.ganti-password';
    protected static ?string $slug = 'ganti-password';
    protected static ?string $navigationLabel = 'Ubah Kata Sandi';
    protected static ?string $title = 'Ubah Kata Sandi';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('current_password')
                    ->label('Password Saat Ini')
                    ->password()
                    ->revealable()
                    ->required()
                    ->currentPassword()
                    ->prefixIcon('heroicon-o-lock-closed')
                    ->helperText('Masukkan password yang sedang digunakan')
                    ->validationMessages([
                        'current_password' => 'Password saat ini tidak sesuai',
                        'required' => 'Password saat ini wajib diisi',
                    ]),

                TextInput::make('password')
                    ->label('Password Baru')
                    ->password()
                    ->revealable()
                    ->required()
                    ->rule(Password::default()->min(8)->mixedCase()->numbers()->symbols())
                    ->same('password_confirmation')
                    ->prefixIcon('heroicon-o-key')
                    ->helperText('Minimal 8 karakter dengan kombinasi huruf besar, kecil, angka dan simbol')
                    ->validationMessages([
                        'min' => 'Password minimal 8 karakter',
                        'same' => 'Konfirmasi password tidak sama',
                        'required' => 'Password baru wajib diisi',
                    ]),

                TextInput::make('password_confirmation')
                    ->label('Konfirmasi Password Baru')
                    ->password()
                    ->revealable()
                    ->required()
                    ->prefixIcon('heroicon-o-check-circle')
                    ->helperText('Ulangi password baru untuk konfirmasi')
                    ->validationMessages([
                        'required' => 'Konfirmasi password wajib diisi',
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            // Update password
            auth()->user()->update([
                'password' => Hash::make($data['password']),
            ]);

            // Clear form
            $this->form->fill();

            // Success notification
            Notification::make()
                ->success()
                ->title('Password Berhasil Diubah')
                ->body('Password Anda telah berhasil diperbarui.')
                ->duration(5000)
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Gagal Mengubah Password')
                ->body('Terjadi kesalahan saat mengubah password. Silakan coba lagi.')
                ->duration(5000)
                ->send();
        }
    }
    protected function getHeaderActions(): array
    {
        return [

            \Filament\Actions\Action::make('kembali')
                ->label('Kembali')
                ->url(route('filament.user.pages.profil-saya'))
                ->color('gray'),
        ];
    }
}
