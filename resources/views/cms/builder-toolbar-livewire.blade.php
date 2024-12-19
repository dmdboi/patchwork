<div class="bg-gray-100">

    <!-- Toolbar -->
    <div class="flex justify-between px-6 py-6 bg-white border-b dark:border-gray-700">
        <div class="flex justify-between w-full">
            <div>
                {{ $this->editPageAction }}
            </div>

            <div>
                {{ $this->previewPageAction }}

                {{ $this->savePageAction }}
            </div>
        </div>
    </div>

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
