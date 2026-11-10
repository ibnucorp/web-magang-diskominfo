<x-filament-panels::page>
    <div class="bg-gray-50 rounded-lg shadow-md p-6">

        <!-- Grid Informasi -->
        <div class="grid grid-cols-1 md:grid-cols-2  gap-6">
            <!-- Data Pribadi -->
            <div class="space-y-4">
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="font-semibold text-lg mb-4 border-b pb-2">Data Pribadi</h3>
                    <ul class="space-y-3">
                        <li class="flex">
                            <span class="w-32 font-medium text-gray-600">Nama Lengkap</span>
                            <span class="text-gray-800">{{ $user->nama_lengkap }}</span>
                        </li>
                        <li class="flex">
                            <span class="w-32 font-medium text-gray-600">NISN/NPM</span>
                            <span class="text-gray-800">{{ $user->nomor_pokok }}</span>
                        </li>
                        <li class="flex">
                            <span class="w-32 font-medium text-gray-600">Username</span>
                            <span class="text-gray-800">{{ $user->username }}</span>
                        </li>
                        <li class="flex">
                            <span class="w-32 font-medium text-gray-600">Tempat Lahir</span>
                            <span class="text-gray-800">
                                {{ $user->tempat_lahir }}
                            </span>
                        </li>
                        <li class="flex">
                            <span class="w-32 font-medium text-gray-600">Tanggal Lahir</span>
                            <span class="text-gray-800">{{ \Carbon\Carbon::parse($user->tgl_lahir)->translatedFormat('d F Y') }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Kontak & Lainnya -->
            <div class="space-y-4">
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="font-semibold text-lg mb-4 border-b pb-2">Kontak & Institusi</h3>
                    <ul class="space-y-3">
                        <li class="flex">
                            <span class="w-32 font-medium text-gray-600">Email</span>
                            <span class="text-gray-800">{{ $user->email }}</span>
                        </li>
                        <li class="flex">
                            <span class="w-32 font-medium text-gray-600">Telepon</span>
                            <span class="text-gray-800">{{ $user->telpon }}</span>
                        </li>
                        <li class="flex">
                            <span class="w-32 font-medium text-gray-600">Jurusan</span>
                            <span class="text-gray-800">{{ $user->jurusan }}</span>
                        </li>
                        @if ($user->role === 'peserta')
                        <li class="flex">
                            <span class="w-32 font-medium text-gray-600">Instansi</span>
                            <span class="text-gray-800">{{ $user->instansi }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>


    </div>
</x-filament-panels::page>
