<x-filament-panels::page>
    @php
        $user = auth()->user();
    @endphp
        @if ($user->role === 'peserta')
            @php
                $pendaftaran = $user->pendaftaran()->latest()->first();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Konten panduan magang --}}
                <div class="md:col-span-2">
                    <h2 class="text-xl font-bold mb-2">Panduan Magang</h2>
                    <div class="prose max-w-full">
                        <ul class="list-disc pl-6 text-gray-600">
                            <li>Lengkapi data diri di halaman Profil Saya</li>
                            <li>Pastikan semua data telah diisi dengan benar.</li>
                            <li>Di halaman Pendaftaran Magang, isi periode magang.</li>
                            <li>Upload surat pengantar magang.</li>
                            <li>Setelah kamu kirim pendaftaran. Silahkan tunggu verifikasi dari admin.</li>
                            <li>Keputusan diterima atau ditolak akan dilakukan oleh kepala dinas.</li>
                            <li>Periksa status pendaftaran kamu secara berkala.</li>
                        </ul>
                    </div>
                </div>

                {{-- Status pendaftaran --}}
                <div class="bg-white shadow-lg rounded-xl p-4">
                    <h3 class="font-semibold text-lg mb-2">Status Pendaftaran</h3>
                    @if ($pendaftaran)
                        <p class="text-sm">Status: <span class="font-bold">{{ ucfirst(str_replace('_', ' ', $pendaftaran->status_pendaftaran)) }}</span></p>
                        @if ($pendaftaran->komentar)
                            <p class="mt-2 text-sm text-gray-600">Komentar: "{{ $pendaftaran->komentar }}"</p>
                        @endif
                    @else
                        <p class="text-sm text-gray-500">Belum ada pendaftaran.</p>
                    @endif
                </div>
            </div>

            {{-- Kontak admin --}}
            <div class="mt-12 p-4 border-t text-sm">
                <h2 class="text-xl font-bold mb-2">Dukungan</h2>
                <p>Butuh bantuan? Hubungi admin melalui <a href="https://wa.me/089627749528" class="text-blue-600 underline">WhatsApp</a></p>
            </div>
        @else

<!-- Stats Cards -->
        @livewire(\App\Filament\Widgets\StatsOverview::class)
        {{-- Untuk admin dan kadin tampilkan tabel --}}
        <div class="mb-6">

            @if ($user->role === 'admin')
                <h2 class="text-xl font-bold">Selamat Datang, Admin!</h2>
                <p class="text-gray-500">Berikut adalah daftar pendaftaran magang yang perlu diverifikasi.</p>
            @elseif ($user->role === 'kadin')
                <h2 class="text-xl font-bold">Selamat Datang, Kepala Dinas!</h2>
                <p class="text-gray-500">Berikut adalah daftar pendaftaran magang yang menunggu keputusan Anda.</p>
            @endif
        </div>
            <div>
                {{ $this->table }}
            </div>

    @endif
</x-filament-panels::page>
