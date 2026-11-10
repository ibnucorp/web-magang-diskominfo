<x-filament-panels::page>

        <form wire:submit.prevent="save">
            {{ $this->form }}
            <div class="pt-4">
            <x-filament::button type="submit" class="mt-4">
                Simpan Perubahan
            </x-filament::button>
            </div>
        </form>

</x-filament-panels::page>
