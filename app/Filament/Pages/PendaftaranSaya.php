<?php

namespace App\Filament\Pages;

use App\Models\Pendaftaran;
use Carbon\Carbon;
use Filament\Actions\Concerns\CanNotify;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class PendaftaranSaya extends Page
{
    use CanNotify;
    use WithFileUploads;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Magang';
    protected static string $view = 'filament.pages.pendaftaran-saya';
    protected static ?string $title = 'Pendaftaran Magang';

    public ?array $data = [];
    public ?string $surat_pengantar = null;

    public function mount(): void
    {
        if (!self::canView()) {
            abort(403);
        }
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([

            DatePicker::make('tgl_mulai')
                ->required()
                ->native(false)
                ->label('Tanggal Mulai')
                ->displayFormat('d F Y')
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
        ])->statePath('data');
    }

    public function submit(): void
    {
        $user = auth()->user();
        // Pengkondisian hanya bisa daftar jika posisi sudah ditolak atau diterima
        if ($user->pendaftaran()->whereNotIn('status_pendaftaran', ['ditolak', 'diterima'])->exists()) {
            Notification::make()
                ->danger()
                ->title('Pendaftaran Berhasil')
                ->body('Kamu sudah mengajukan pendaftaran.')
                ->send();
            return;
        }

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
            $formData = $this->form->getState();
            $user->pendaftaran()->create(
                [
                    'tgl_mulai' => $formData['tgl_mulai'],
                    'tgl_selesai' => $formData['tgl_selesai'],
                    'surat_pengantar' => $formData['surat_pengantar'],
                    'status_pendaftaran' => 'menunggu_verifikasi',
                ]
            );

            Notification::make()
                ->success()
                ->title('Pendaftaran Berhasil')
                ->body('Pendaftaran kamu telah berhasil dikirim.')
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

    public function getPendaftaranAktif(): ?Pendaftaran
    {
        return Pendaftaran::where('user_id', auth()->id())
            ->latest()
            ->first();
    }
    protected static function canView(): bool
    {
        return Auth::user()->role !== 'admin' && Auth::user()->role !== 'kadin';
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'peserta';
    }
    public function editPendaftaran()
    {
        $pendaftaran = $this->getPendaftaranAktif();

        $this->form->fill([
            'tgl_mulai' => $pendaftaran->tgl_mulai,
            'tgl_selesai' => $pendaftaran->tgl_selesai,
            'surat_pengantar' => null,
        ]);
    }
}
