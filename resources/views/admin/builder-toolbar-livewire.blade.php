<div>
    <!-- Toolbar -->
    <x-toolbar>
        <x-slot:left>
            <livewire:back-to-admin-button :post="$page" />
        </x-slot:left>

        <x-slot:right>
            {{ $this->previewPageAction }}

            {{ $this->savePageAction }}
        </x-slot:right>
    </x-toolbar>

    <!-- Preview -->
    @if ($this->preview)
        <x-sections-renderer :page="$page" />
    @endif

    <!-- Editor -->
    @if (!$this->preview)
        <div class="w-full p-4 mx-auto md:max-w-3xl">
            <form wire:submit="saveSections" id="{{ $this->page->Eid }}">
                <div class="flex justify-end w-full mb-4">

                </div>

                {{ $this->form }}
            </form>
        </div>
    @endif

    <x-filament-actions::modals />
</div>
