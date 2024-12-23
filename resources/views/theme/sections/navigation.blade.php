@php
    $items = menu($section['data']['menu']);
@endphp

<header class="max-w-3xl p-4 mx-auto">
    <nav class="relative flex items-center justify-between w-full mx-auto sm:flex sm:items-center" aria-label="global">
        <a class="flex-none text-xl font-semibold" href="/" aria-label="Brand">Patchwork</a>
        <div class="flex flex-row items-center justify-center gap-x-5 sm:gap-x-7">

            @foreach ($items as $item)
                <a href="{{ $item->url }}" @if ($item->new_tab) target="_blank" @endif
                    class="flex-none text-[1.05rem] font-medium hover:text-foreground/75" aria-label="Nav Menu Item">
                    <span
                        class="flex-1 truncate hover:underline {{ $item->is_active ? 'text-purple-500' : 'text-black' }}">
                        {{ $item->title }}
                    </span>
                </a>
            @endforeach
        </div>
    </nav>
</header>
