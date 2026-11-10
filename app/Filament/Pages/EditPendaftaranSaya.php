<?php

namespace App\Filament\Pages;

use App\Filament\Resources\PendaftaranResource;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanNotify;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class EditPendaftaranSaya extends Page
{
    use CanNotify;
    protected static ?string $navigationIcon = null;

    protected static string $view = 'filament.pages.edit-pendaftaran-saya';
    protected static ?string $title = 'Edit Pendaftaran';
    protected static ?string $slug = 'edit-pendaftaran-saya';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    public $pendaftaran;
    public $peserta;

    public function mount(): void
    {
        // Cari data pendaftaran user yang login
        $this->pendaftaran = Pendaftaran::with('user')
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->peserta = $this->pendaftaran->user;

        // Redirect jika user belum ada pendaftaran
        if (!$this->pendaftaran) {
            redirect()->route('filament.user.pages.pendaftaran-saya')
                ->with('warning', 'Anda belum memiliki pendaftaran');
            return;
        }

        // Isi data form dengan data pendaftaran yang sudah ada
        $this->form->fill([
            'tgl_mulai' => $this->pendaftaran->tgl_mulai,
            'tgl_selesai' => $this->pendaftaran->tgl_selesai,
            'surat_pengantar' => $this->pendaftaran->surat_pengantar,
        ]);
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('tgl_mulai')
                    ->required()
                    ->native(false)
                    ->label('Tanggal Mulai')
                    ->displayFormat('d F Y')
                    ->minDate(now()->addDay())
                    ->closeOnDateSelection()
                    ->live()->helperText('Pendaftaran hanya diproses saat hari kerja.'),
                DatePicker::make('tgl_selesai')
                    ->required()
                    ->label('Tanggal Selesai')
                    ->native(false)
                    ->closeOnDateSelection()
                    ->displayFormat('d F Y')
                    ->helperText('Durasi magang minimal 1 bulan.'),
                FileUpload::make('surat_pengantar')
                    ->label('Upload Surat Pengantar dalam bentuk PDF')
                    ->disk('public')
                    ->directory('surat_pengantar')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(2048)
                    ->helperText('*Upload file PDF maksimal 2MB')
                    ->required()
                    ->openable()
                    ->downloadable()
                    ->getUploadedFileNameForStorageUsing(function ($file): string {
                        $user = auth()->user();
                        $timestamp = now()->format('Ymd');
                        $namaLengkap = str_replace(' ', '_', strtolower($user->nama_lengkap));
                        $extension = $file->getClientOriginalExtension();
                        return "{$timestamp}_{$namaLengkap}.{$extension}";
                    })
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        if (empty($this->data['tgl_mulai']) || empty($this->data['tgl_selesai'])) {
            Notification::make()
                ->danger()
                ->title('Validasi Gagal')
                ->body('Tanggal mulai dan selesai harus diisi.')
                ->send();
            return;
        }

        if (empty($this->data['surat_pengantar'])) {
            Notification::make()
                ->danger()
                ->title('Validasi Gagal')
                ->body('Surat pengantar harus diupload.')
                ->send();
            return;
        }

        try {
            $user = auth()->user();
            $formData = $this->form->getState();
            $user->pendaftaran()->update(
                [
                    'tgl_mulai' => $formData['tgl_mulai'],
                    'tgl_selesai' => $formData['tgl_selesai'],
                    'surat_pengantar' => $formData['surat_pengantar'],
                    'status_pendaftaran' => 'menunggu_verifikasi',
                ]
            );

            Notification::make()
                ->success()
                ->title('Update Pendaftaran Berhasil')
                ->body('Pendaftaran kamu telah berhasil dikirim, tunggu Verifikasi.')
                ->send();
            $this->data = [];
            redirect()->back();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('kembali')
                ->label('Kembali')
                ->url(route('filament.user.pages.pendaftaran-saya'))
                ->color('gray'),
        ];
    }

    public static function getRouteName(?string $panel = null): string
    {
        return 'filament.pages.edit-pendaftaran-saya';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        // Hanya 'peserta' yang bisa mengakses
        if ($user && $user->role === 'peserta') {
            //  Harus memiliki pendaftaran
            return Pendaftaran::where('user_id', $user->id)->exists();
        }

        return false;
    }
}
