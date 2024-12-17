<div class="bg-gray-100">

    <!-- Toolbar -->
    <div class="flex justify-between px-6 py-6 bg-white border-b dark:border-gray-700">
        @if (auth()->user())
            <div class="flex justify-start gap-4">
                {{ $this->editPageAction }}
                {{ $this->previewPageAction }}
            </div>
        @endif
    </div>

    <!-- Preview -->
    @if ($this->preview)
        <x-filament-cms::sections-renderer />
    @endif

    <!-- Editor -->
    @if (!$this->preview)
        <div class="w-full p-4 mx-auto md:max-w-3xl">
            <form wire:submit="saveSections" id="{{ $this->page->Eid }}">
                <div class="flex justify-end w-full mb-4">
                    <x-filament::button wire:click="saveSections" color="success">
                        Save Changes
                    </x-filament::button>
                </div>

                {{ $this->form }}
            </form>
        </div>
    @endif

    <x-filament-actions::modals />
</div>
