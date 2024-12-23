@props(['menuKey', 'component' => null])

@php
    // Assuming $menuKey is passed to this component
    $menuKey = $menuKey ?? 'header'; // Replace 'default_menu_key' with your default menu key if needed
    $menu = \App\Models\Menu::where('key', $menuKey)->first();

    $menuItems = \App\Models\MenuItem::where('menu_id', $menu->id)->orderBy('order')->get();
@endphp

@if(!$component)
<nav class="space-x-2">
<!-- If no component is passed, render the menu items as links -->


@foreach ($menuItems as $item)
    @php
        $url = $item['route'] ? $item['route'] : $item['url'];
        $isActive = url()->current() === $url;
    @endphp

    <a href="{{ $url }}" @if ($item['new_tab']) target="_blank" @endif>
        <span class="flex-1 truncate  hover:underline {{ $isActive ? 'text-purple-500' : 'text-blue-500 ' }}">
            {{ $item['title'] }}
        </span>
    </a>
@endforeach
</nav>
@else
<!-- If a component is passed, render the menu items as component -->
    @component(
        $component,
        [
            'menuItems' => $menuItems,
        ]
    )
    @endcomponent
@endif