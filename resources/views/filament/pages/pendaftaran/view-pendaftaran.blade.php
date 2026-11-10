@php
    use Carbon\Carbon;
    $user = $this->getUser();

    // Fungsi pembantu
    $formatDate = fn($date) => $date ? Carbon::parse($date)->locale('id')->translatedFormat('d F Y') : '-';
    $formatStatus = fn($status) => ucfirst(str_replace('_', ' ', $status));
@endphp
<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Profil Peserta --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-user class="w-5 h-5"/>
                    Profil Peserta
                </div>
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Data Pribadi --}}
                <div class="space-y-3">
                    <h4 class="font-medium text-gray-900 border-b border-gray-200 pb-2">Data Pribadi</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500">Nama Lengkap</span>
                            <p class="font-medium text-gray-900">{{ $user->nama_lengkap }}</p>
                        </div>
                        
                        <div>
                            <span class="text-gray-500">NISN/NPM</span>
                            <p class="font-medium text-gray-900">{{ $user->nomor_pokok }}</p>
                        </div>
                        
                        <div>
                            <span class="text-gray-500">Tempat Lahir</span>
                            <p class="font-medium text-gray-900">{{ $user->tempat_lahir ?: '-' }}</p>
                        </div>
                        
                        <div>
                            <span class="text-gray-500">Tanggal Lahir</span>
                            <p class="font-medium text-gray-900">{{ $formatDate($user->tgl_lahir) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Data Akademik --}}
                <div class="space-y-3">
                    <h4 class="font-medium text-gray-900 border-b border-gray-200 pb-2">Data Akademik</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500">Jurusan</span>
                            <p class="font-medium text-gray-900">{{ $user->jurusan ?: '-' }}</p>
                        </div>
                        
                        <div>
                            <span class="text-gray-500">Instansi</span>
                            <p class="font-medium text-gray-900">{{ $user->instansi ?: '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Kontak --}}
                <div class="space-y-3">
                    <h4 class="font-medium text-gray-900 border-b border-gray-200 pb-2">Informasi Kontak</h4>
                    
                    <div class="grid grid-cols-1 gap-3 text-sm">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-user class="w-4 h-4 text-gray-400"/>
                            <div>
                                <span class="text-gray-500">Username</span>
                                <p class="font-medium text-gray-900">{{ $user->username }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-envelope class="w-4 h-4 text-gray-400"/>
                            <div>
                                <span class="text-gray-500">Email</span>
                                <p class="font-medium text-gray-900">{{ $user->email ?: '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-phone class="w-4 h-4 text-gray-400"/>
                            <div>
                                <span class="text-gray-500">Telepon</span>
                                <p class="font-medium text-gray-900">{{ $user->telpon ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::section>

                {{-- Informasi Pendaftaran --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-5 h-5"/>
                    Informasi Pendaftaran
                </div>
            </x-slot>

            <div class="space-y-4">
                {{-- Status Card --}}
                <div class="p-4 rounded-lg border-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium">Status Pendaftaran</p>
                            <p class="text-lg font-bold">{{ $formatStatus($record->status_pendaftaran) }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($record->status_pendaftaran === 'diterima')
                                <x-heroicon-o-check-circle class="w-8 h-8"/>
                            @elseif($record->status_pendaftaran === 'ditolak')
                                <x-heroicon-o-x-circle class="w-8 h-8"/>
                            @else
                                <x-heroicon-o-document class="w-8 h-8"/>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Periode --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2 mb-1">
                            <x-heroicon-o-calendar class="w-4 h-4 text-gray-500"/>
                            <span class="text-sm text-gray-500">Tanggal Mulai</span>
                        </div>
                        <p class="font-semibold text-gray-900">{{ $formatDate($record->tgl_mulai) }}</p>
                    </div>
                    
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2 mb-1">
                            <x-heroicon-o-calendar class="w-4 h-4 text-gray-500"/>
                            <span class="text-sm text-gray-500">Tanggal Selesai</span>
                        </div>
                        <p class="font-semibold text-gray-900">{{ $formatDate($record->tgl_selesai) }}</p>
                    </div>
                </div>

                {{-- Durasi --}}
                @if($record->tgl_mulai && $record->tgl_selesai)
                    @php
                        $durasi = Carbon::parse($record->tgl_mulai)->diffInDays(Carbon::parse($record->tgl_selesai)) + 1;
                    @endphp
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-clock class="w-4 h-4 text-blue-600"/>
                            <span class="text-sm text-blue-600">Durasi Magang</span>
                            <span class="ml-auto font-semibold text-blue-900">{{ $durasi }} hari</span>
                        </div>
                    </div>
                @endif

                {{-- Komentar --}}
                @if($record->komentar)
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0"/>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-amber-800 mb-1">Komentar</p>
                                <p class="text-sm text-amber-700">{{ $record->komentar }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Surat Pengantar --}}
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-document-arrow-down class="w-5 h-5 text-gray-500"/>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Surat Pengantar</p>
                                <p class="text-xs text-gray-500">
                                    @if($record->surat_pengantar)
                                        File tersedia
                                    @else
                                        Tidak ada file
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        @if($record->surat_pengantar)
                            <x-filament::button
                                tag="a"
                                href="{{ Storage::url($record->surat_pengantar) }}"
                                target="_blank"
                                size="sm"
                                color="primary"
                                icon="heroicon-o-arrow-top-right-on-square"
                            >
                                Lihat File
                            </x-filament::button>
                        @else
                            <x-filament::badge color="gray">
                                Tidak ada
                            </x-filament::badge>
                        @endif
                    </div>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
