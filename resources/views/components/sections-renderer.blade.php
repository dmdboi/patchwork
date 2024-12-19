@props(['page'])

@if ($page->meta('sections') && count($page->meta('sections')))
    @foreach ($page->meta('sections') as $section)
        @php $getSection = section($section['type']) @endphp

        @if ($getSection)
            @include($getSection->view, ['section' => $section])
        @endif
    @endforeach
@else
    {{ $page->body }}
@endif
