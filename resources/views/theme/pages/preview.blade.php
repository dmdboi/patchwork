<x-layout>
    <!-- Toolbar -->
    <x-toolbar>
        <x-slot:left>
            <livewire:back-to-admin-button :post="$page" />
        </x-slot:left>
    </x-toolbar>

    <x-sections-renderer :page="$page" />
</x-layout>
