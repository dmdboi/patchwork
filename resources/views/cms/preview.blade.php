<x-layout>
    <div class="mx-6 my-2 border-b border-black">
        <a href="/admin/posts/{{ $page->id }}/edit" class="block py-2 text-blue-500 underline">Back to Admin</a>
    </div>


    <div class="mx-6">
        <x-menu menu="footer" />
    </div>

    <x-sections-renderer :page="$page" />
</x-layout>
