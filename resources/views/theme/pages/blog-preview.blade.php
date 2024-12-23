<x-layout>
    <!-- Toolbar -->
    <x-toolbar>
        <x-slot:left>
            <livewire:back-to-admin-button :post="$post" />
        </x-slot:left>
    </x-toolbar>

    <div class="flex items-center justify-center m-6">
        <div class="p-4 border border-black">
            <h1 class="text-2xl font-bold">
                {{ $post->title }}
            </h1>

            <p class="mt-2">
                {!! nl2br($post->body) !!}
            </p>
        </div>
    </div>
</x-layout>
