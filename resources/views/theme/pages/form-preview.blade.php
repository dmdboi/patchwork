<x-layout>

    <div class="mx-6 my-2">
        <a href="/admin/forms" class="block py-2 text-blue-500 underline">Back to Admin</a>
    </div>

    <div class="flex items-center justify-center max-w-2xl m-6 mx-auto">
        <div class="w-full p-4 space-y-4 border border-black">
            <h1 class="text-2xl font-bold">
                Form
            </h1>

            <livewire:form-preview :form_id="$form->id" />
        </div>
    </div>
</x-layout>
