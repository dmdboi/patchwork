@php
    $record = App\Models\MenuItem::find($getState());
@endphp

<div class="flex justify-start gap-2">
    <div>
        {{ $record->title }}
    </div>
</div>
