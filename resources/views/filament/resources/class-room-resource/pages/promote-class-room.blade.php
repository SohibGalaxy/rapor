<x-filament-panels::page>
    <x-filament-panels::form wire:submit="promote">
        {{ $this->form }}

        <x-slot name="actions">
            {{ $this->getFormActions() }}
        </x-slot>
    </x-filament-panels::form>
</x-filament-panels::page>
