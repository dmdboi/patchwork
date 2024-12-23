@if (!$component)
    <nav class="space-x-2">
        <!-- Render the menu items as links -->
        @foreach ($menuItems as $item)
            <a href="{{ $item->url }}" @if ($item->new_tab) target="_blank" @endif>
                <span
                    class="flex-1 truncate hover:underline {{ $item->is_active ? 'text-purple-500' : 'text-blue-500' }}">
                    {{ $item->title }}
                </span>
            </a>
        @endforeach
    </nav>
@else
    <!-- Render the menu items as a custom component -->
    @component($component, ['menuItems' => $menuItems])
    @endcomponent
@endif
