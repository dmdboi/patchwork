<x-layout>

    <div class="mx-6 my-2">
        <a href="/admin/posts/{{ $post->id }}/edit" class="block py-2 text-blue-500 underline">Back to Admin</a>
    </div>

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
