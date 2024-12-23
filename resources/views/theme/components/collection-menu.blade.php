@php
    $posts = getPostsByCollection($section['data']['collection']);
@endphp

<nav class="max-w-3xl mx-auto space-x-2 text-center">
    @foreach ($posts as $post)
        <a href="/{{ $post->collection->slug }}/{{ $post->slug }}" class="flex items">
            <span class="flex-1 text-blue-500 truncate hover:underline">
                {{ $post->title }}
            </span>
        </a>
    @endforeach
</nav>