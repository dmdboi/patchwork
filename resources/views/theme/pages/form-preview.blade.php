<x-layout>
    <!-- Toolbar -->
    <x-toolbar>
        <x-slot:left>
            <livewire:back-to-admin-button >
        </x-slot:left>
    </x-toolbar>

    <div class="flex items-center justify-center max-w-2xl m-6 mx-auto">
        <div class="w-full p-4 space-y-4 border border-black">
            <h1 class="text-2xl font-bold">
                Form
            </h1>

            <livewire:form-preview :form_id="$form->id" />
        </div>
    </div>
</x-layout>
