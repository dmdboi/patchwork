@props(['livewire' => null, 'page'])

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['fi min-h-screen'])>

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    @if ($favicon = filament()->getFavicon())
        <link rel="icon" href="{{ $favicon }}" />
    @endif

    <title>
        {{ filled($title = strip_tags($livewire?->getTitle() ?? '')) ? "{$title} - " : '' }}
        {{ strip_tags(filament()->getBrandName()) }}
    </title>

    <style>
        [x-cloak],
        [x-cloak='1'] {
            display: none !important;
        }

        [x-cloak='-lg'] {
            display: none !important;
            @media (max-width: 1023px);
        }

        [x-cloak='lg'] {
            display: none !important;
            @media (min-width: 1024px);
        }
    </style>

    @filamentStyles
    {{ filament()->getTheme()->getHtml() }}
    {{ filament()->getFontHtml() }}

    <style>
        :root {
            --font-family: '{!! filament()->getFontFamily() !!}';
            --sidebar-width: {{ filament()->getSidebarWidth() }};
            --collapsed-sidebar-width: {{ filament()->getCollapsedSidebarWidth() }};
            --default-theme-mode: {{ filament()->getDefaultThemeMode()->value }};
        }
    </style>

    @stack('styles')
    @livewireStyles
</head>

<body
    class="min-h-screen antialiased font-normal fi-body fi-panel-admin bg-gray-50 text-gray-950 dark:bg-gray-950 dark:text-white">

    @livewire(\App\Livewire\BuilderToolbar::class, ['page' => $page])

    @livewire(Filament\Livewire\Notifications::class)
    @filamentScripts(withCore: true)

    @stack('scripts')
    @stack('modals')
    @livewireScripts
</body>

</html>
