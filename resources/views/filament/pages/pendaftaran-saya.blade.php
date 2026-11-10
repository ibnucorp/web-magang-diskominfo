<x-filament-panels::page>
    @php
        $pendaftaran = $this->getPendaftaranAktif();
        $status = $pendaftaran?->status_pendaftaran;
        $user = auth()->user();
    @endphp

    {{-- Kondisi jika sudah daftar atau belum  --}}
    @if (! $pendaftaran )
        <hr>
        <h2 class="text-2xl font-bold mb-0">Form Pendaftaran</h2>
        <p>Sebelum daftar, pastikan semua data diri kamu sesuai dengan yang sebenarnya.</p>
        <form wire:submit.prevent="submit" class="space-y-4">
            {{ $this->form }}
            <div class="mt-4">    
                <x-filament::button type="submit">
                    Kirim Pendaftaran
                </x-filament::button>
            </div>
        </form>
    @else
<div class="mt-6 space-y-6">
    <!-- Progress Bar Tahapan Pendaftaran -->
    <x-filament::card>
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Status Pendaftaran</h3>
            @if ($status === "diterima" || $status === "ditolak")
                Keputusan :
                <span class="font-bold text-xl">    
                    {{ Str::ucfirst($status) }}
                </span>
                <div class="md:col-span-1">
                    <a href="{{ route('print.surat.balasan', ['pendaftaran' => $pendaftaran]) }}"
                        target="_blank" 
                       class="inline-flex items-center text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                        <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" />
                            <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" />
                        </svg>
                        Download Surat Balasan
                    </a>
                </div>
            @endif
            <div class="mt-6">
                <div class="flex items-center justify-between">
                    @php
                        $steps = [
                            'daftar' => ['label' => 'Pendaftaran', 'status' => 'completed'],
                            'verifikasi' => ['label' => 'Verifikasi', 'status' => ($status === 'menunggu_keputusan' || ($status === 'diterima' || $status === 'ditolak')  ? 'completed' : 'current')],
                            'keputusan' => ['label' => 'Keputusan', 'status' => $status === 'menunggu_keputusan' ? 'current' : (($status === 'ditolak' || $status === 'diterima') ? 'completed' : 'pending')],
                            'hasil' => ['label' => "Hasil", 'status' => $status === 'diterima' || $status === 'ditolak' ? 'completed' : 'pending']
                        ];
                    @endphp
                    
                    @foreach($steps as $key => $step)
                        <div class="flex flex-col items-center space-y-2">
                            <div @class([
                                'flex items-center justify-center w-10 h-10 rounded-full',
                                'bg-primary-600 text-white' => $step['status'] === 'completed',
                                'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300' => $step['status'] === 'pending',
                                'ring-2 ring-primary-600 bg-white dark:bg-gray-800' => $step['status'] === 'current'
                            ])>
                                @switch($key)
                                    @case('daftar') 1 @break
                                    @case('verifikasi') 2 @break
                                    @case('keputusan') 3 @break
                                    @case('hasil') 4 @break
                                @endswitch
                            </div>
                            <span @class([
                                'text-xs font-medium',
                                'text-primary-600 dark:text-primary-400' => $step['status'] === 'completed',
                                'text-gray-500 dark:text-gray-400' => $step['status'] === 'pending',
                                'font-bold text-gray-900 dark:text-white' => $step['status'] === 'current'
                            ])>
                                {{ $step['label'] }}
                            </span>
                        </div>
                        
                        @if(!$loop->last)
                            <div class="flex-1 h-1 mx-2 bg-gray-200 dark:bg-gray-700 relative">
                                <div @class([
                                    'absolute inset-0 h-full',
                                    'bg-primary-600' => in_array($steps[array_keys($steps)[$loop->index + 1]]['status'], ['completed', 'current']),
                                    'bg-gray-200 dark:bg-gray-700' => $steps[array_keys($steps)[$loop->index + 1]]['status'] === 'pending'
                                ])></div>
                            </div>
                        @endif
                    @endforeach
                </div>
                
            </div>
        </div>
    </x-filament::card>

    <!-- Informasi Pendaftaran -->
    <x-filament::card>
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Pendaftaran</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Mulai</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($pendaftaran->tgl_mulai)->translatedFormat('d F Y') }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Selesai</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($pendaftaran->tgl_selesai)->translatedFormat('d F Y') }}</p>
                </div>
                
                <div class="md:col-span-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Surat Pengantar</p>
                    <a href="{{ Storage::url($pendaftaran->surat_pengantar) }}" 
                       class="inline-flex items-center text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                        <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" />
                            <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" />
                        </svg>
                        Download Surat Pengantar
                    </a>
                </div>

                @if ($pendaftaran->komentar)
                    <div class="md:col-span-2">
                        <div class="mt-3 text-sm text-yellow-300">
                            <strong>Komentar</strong>
                            <p>
                                {{ $pendaftaran->komentar }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::card>

    @if ($status === 'revisi')
        <x-filament::button 
            href="{{ route('filament.user.pages.edit-pendaftaran-saya') }}"
            tag="a"
            icon="heroicon-o-pencil"
            color="warning"
            class="w-full md:w-auto"
        >
            Edit Pendaftaran
        </x-filament::button>
    @endif
</div>
    @endif
</x-filament-panels::page>
