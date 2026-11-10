<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class EditProfilSaya extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.pages.edit-profil-saya';
    protected static ?string $title = 'Edit Profil';
    protected static ?string $slug = 'edit-profil-saya';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    public $user;

    public function mount()
    {
        // Cari data pendaftaran user yang login
        $this->user = auth()->user();

        $this->form->fill([
            'username' => $this->user->username,
            'nama_lengkap' => $this->user->nama_lengkap,
            'nomor_pokok' => $this->user->nomor_pokok,
            'jurusan' => $this->user->jurusan,
            'tempat_lahir' => $this->user->tempat_lahir,
            'tgl_lahir' => $this->user->tgl_lahir,
            'instansi' => $this->user->instansi,
            'email' => $this->user->email,
            'telpon' => $this->user->telpon,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Akun')
                ->icon('heroicon-o-user-circle')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('username')
                            ->label('Username')
                            ->readOnly()
                            ->helperText('Username tidak dapat diubah')
                            ->prefixIcon('heroicon-o-at-symbol'),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->helperText('Gunakan email aktif yang valid')
                            ->prefixIcon('heroicon-o-envelope')
                            ->validationMessages([
                                'email' => 'Format email tidak valid',
                                'unique' => 'Email sudah terdaftar dalam sistem',
                                'required' => 'Email wajib diisi',
                            ]),
                    ]),
                ]),

            Section::make('Data Pribadi')
                ->icon('heroicon-o-identification')
                ->schema([
                    TextInput::make('nama_lengkap')
                        ->label('Nama Lengkap')
                        ->required()
                        ->minLength(3)
                        ->maxLength(100)
                        ->regex('/^[\p{L}\s\-\.\']+$/u')
                        ->helperText('Minimal 3 karakter. Hanya huruf, spasi, titik (.), apostrof (\') atau tanda hubung (-)')
                        ->prefixIcon('heroicon-o-user')
                        ->validationMessages([
                            'regex' => 'Nama hanya boleh berisi huruf, spasi, titik (.), apostrof (\') dan tanda hubung (-)',
                            'min' => 'Nama minimal 3 karakter',
                            'max' => 'Nama maksimal 100 karakter',
                            'required' => 'Nama lengkap wajib diisi',
                        ]),

                    Grid::make(2)->schema([
                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->required()
                            ->minLength(2)
                            ->maxLength(50)
                            ->regex('/^[\p{L}\s\-\.]+$/u')
                            ->helperText('Contoh: Palembang, Jakarta')
                            ->prefixIcon('heroicon-o-map-pin')
                            ->validationMessages([
                                'regex' => 'Tempat lahir hanya boleh berisi huruf, spasi, titik dan tanda hubung',
                                'min' => 'Tempat lahir minimal 2 karakter',
                                'required' => 'Tempat lahir wajib diisi',
                            ]),

                        DatePicker::make('tgl_lahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->format('Y-m-d')
                            ->maxDate(now()->subYears(13))
                            ->minDate(now()->subYears(60))
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->validationMessages([
                                'required' => 'Tanggal lahir wajib diisi',
                                'date' => 'Format tanggal tidak valid',
                                'before' => 'Umur minimal 13 tahun',
                            ]),
                    ]),

                    TextInput::make('telpon')
                        ->label('Nomor Telepon')
                        ->required()
                        ->tel()
                        ->regex('/^(\+62|62|0)[8][1-9][0-9]{6,9}$/')
                        ->helperText('Format: 08xxxxxxxxxx')
                        ->prefixIcon('heroicon-o-phone')
                        ->validationMessages([
                            'regex' => 'Format nomor telepon tidak valid. Gunakan format Indonesia yang benar',
                            'required' => 'Nomor telepon wajib diisi',
                        ]),
                ]),

            Section::make('Data Akademik')
                ->icon('heroicon-o-academic-cap')
                ->schema([
                    TextInput::make('nomor_pokok')
                        ->label('NISN/NPM')
                        ->required()
                        ->regex('/^[A-Za-z0-9\-\.]{8,20}$/')
                        ->minLength(8)
                        ->maxLength(20)
                        ->helperText('8-20 karakter: huruf, angka, titik, atau tanda hubung')
                        ->prefixIcon('heroicon-o-identification')
                        ->validationMessages([
                            'regex' => 'Format NISN/NPM tidak valid',
                            'unique' => 'NISN/NPM sudah terdaftar dalam sistem',
                            'required' => 'NISN/NPM wajib diisi',
                            'min' => 'NISN/NPM minimal 8 karakter',
                            'max' => 'NISN/NPM maksimal 20 karakter',
                        ]),

                    Grid::make(2)->schema([
                        TextInput::make('jurusan')
                            ->label('Program Studi/Jurusan')
                            ->required()
                            ->minLength(3)
                            ->maxLength(100)
                            ->helperText('Contoh: Teknik Informatika, Desain Komunikasi Visual')
                            ->prefixIcon('heroicon-o-book-open')
                            ->validationMessages([
                                'required' => 'Jurusan wajib diisi',
                                'min' => 'Jurusan minimal 3 karakter',
                                'max' => 'Jurusan maksimal 100 karakter',
                            ]),

                        TextInput::make('instansi')
                            ->label('Nama Institusi')
                            ->required()
                            ->minLength(5)
                            ->maxLength(150)
                            ->helperText('Contoh: Universitas Sriwijaya, SMK Negeri 1 Palembang')
                            ->prefixIcon('heroicon-o-building-office-2')
                            ->validationMessages([
                                'required' => 'Nama institusi wajib diisi',
                                'min' => 'Nama institusi minimal 5 karakter',
                                'max' => 'Nama institusi maksimal 150 karakter',
                            ]),
                    ]),
                ]),
        ])
            ->statePath('data');
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

    public function save(): void
    {
        $data = $this->form->getState();

        $this->user->update([
            'username' => $data['username'],
            'nama_lengkap' => $data['nama_lengkap'],
            'nomor_pokok' => $data['nomor_pokok'],
            'jurusan' => $data['jurusan'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tgl_lahir' => $data['tgl_lahir'],
            'instansi' => $data['instansi'],
            'email' => $data['email'],
            'telpon' => $data['telpon'],
        ]);

        Notification::make()
            ->success()
            ->title('Update Profil Berhasil')
            ->body('Profil kamu sudah berhasil diperbarui.')
            ->send();
    }
}
