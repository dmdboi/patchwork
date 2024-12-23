@props(['left' => null, 'right' => null])

<!-- Toolbar -->
<div class="flex justify-between px-6 py-6 bg-white border-b dark:border-gray-700">
    <div class="flex justify-between w-full">
        <div>
            {{ $left }}
        </div>

        @if ($right)
            <div>
                {{ $right }}
            </div>
        @endif
    </div>
</div>
