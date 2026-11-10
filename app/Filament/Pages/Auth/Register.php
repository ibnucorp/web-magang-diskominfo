<?php

namespace App\Filament\Pages\Auth;

use App\Rules\ReCaptcha;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Validation\Rules\Password;

class Register extends BaseRegister
{
    protected static ?string $title = 'Daftar';
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Section::make('Form Registrasi')
                            ->schema([
                                Textarea::make('important_notice')
                                    ->label("Penting!")
                                    ->default("Username tidak dapat kamu ubah.\nBuatlah dengan hati-hati.")
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->autosize()
                                    ->readOnly()
                                    ->columnSpanFull()
                                    ->extraAttributes(['class' => 'bg-yellow-50 border-l-4 border-yellow-400 p-4']),

                                TextInput::make('nama_lengkap')
                                    ->label('Nama Lengkap')
                                    ->regex('/^[\p{L}\s\-\.]+$/u')
                                    ->helperText('Contoh: Adi Tia. Hanya huruf, spasi, titik, atau tanda hubung. Min 3 karakter.')
                                    ->validationMessages([
                                        'regex' => 'Nama hanya boleh berisi huruf, spasi, titik dan tanda hubung',
                                    ])
                                    ->required(),
                                TextInput::make('nomor_pokok')
                                    ->label('NISN/NPM')
                                    ->unique('users', 'nomor_pokok')
                                    ->regex('/^[A-Za-z0-9\-\.]+$/')
                                    ->required()
                                    ->validationMessages([
                                        'regex' => 'Format NISN/NPM tidak valid',
                                        'unique' => 'NISN/NPM sudah terdaftar dalam sistem',
                                    ]),
                                TextInput::make('username')
                                    ->label('Username')
                                    ->unique('users', 'username')
                                    ->minLength(5)
                                    ->maxLength(20)
                                    ->alphaDash()
                                    ->helperText('5-20 karakter. Hanya huruf, angka, dan tanda "-" atau "_"')
                                    ->validationMessages([
                                        'unique' => 'Username sudah digunakan',
                                        'alpha_dash' => 'Username hanya boleh berisi huruf, angka, dan tanda "-" atau "_"',
                                    ])
                                    ->required(),
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
                                        'required' => 'Password wajib diisi',
                                    ]),

                                TextInput::make('password_confirmation')
                                    ->label('Konfirmasi Password')
                                    ->password()
                                    ->revealable()
                                    ->required()
                                    ->prefixIcon('heroicon-o-check-circle')
                                    ->helperText('Ulangi password untuk konfirmasi')
                                    ->validationMessages([
                                        'required' => 'Konfirmasi password wajib diisi',
                                    ]),
                                TextInput::make('recaptcha')
                                    ->label('Recaptcha')
                                    ->required()
                                    ->rule(new ReCaptcha())
                                    ->view('filament.components.recaptcha')
                                    ->validationMessages([
                                        'required' => 'Harap lengkapi verifikasi reCAPTCHA terlebih dahulu.',
                                    ]),
                            ])
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}
