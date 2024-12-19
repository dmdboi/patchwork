<x-layout>
    <div class="mx-6 my-2">
        <a href="/admin/posts/{{ $page->id }}/edit" class="block py-2 text-blue-500 underline">Back to Admin</a>
    </div>


    <x-sections-renderer :page="$page" />
</x-layout>
