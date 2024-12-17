@if ($this->page->meta('sections') && count($this->page->meta('sections')))
    

    @foreach ($this->page->meta('sections') as $section)
        @php $getSection = section($section['type']) @endphp

        @if ($getSection)
            @include($getSection->view, ['section' => $section])
        @endif
    @endforeach
@else
    {{ $this->page->body }}
@endif
