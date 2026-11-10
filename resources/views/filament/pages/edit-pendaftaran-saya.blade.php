<x-filament::page>
    <!-- Informasi Peserta -->
    <x-filament::section>
        <x-slot name="heading">
            Informasi Peserta Magang
        </x-slot>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    Data Pribadi
                <dl class="divide-y divide-gray-200">
                    <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $this->peserta->nama_lengkap }}</dd>
                    </div>
                    <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Nomor Pokok</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $this->peserta->nomor_pokok }}</dd>
                    </div>
                    <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Jurusan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $this->peserta->jurusan }}</dd>
                    </div>
                </dl>
                    Kontak & Instansi
                <dl class="divide-y divide-gray-200">
                    <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $this->peserta->email }}</dd>
                    </div>
                    <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $this->peserta->telpon }}</dd>
                    </div>
                    <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Instansi</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $this->peserta->instansi }}</dd>
                    </div>
                </dl>
            
        </div>
        <div class="pt-4">
            <x-filament::button 
                href="{{ route('filament.user.pages.edit-profil-saya') }}"
                tag="a"
                icon="heroicon-o-pencil"
            >
                Edit Profil 
            </x-filament::button>
        </div>
    </x-filament::section>

    <!-- Form Edit Pendaftaran -->
    <x-filament::section class="mt-6">
        <x-slot name="heading">
            Formulir Pendaftaran Magang
        </x-slot>
        
        <form wire:submit.prevent="save">
            {{ $this->form }}
            
            <div class="pt-4">
                <x-filament::button type="submit">
                    Simpan Perubahan
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament::page>