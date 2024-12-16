<div class="grid grid-cols-1 gap-4 p-4 md:grid-cols-3">
    @foreach($records as $item)
        <div class="flex flex-col overflow-hidden bg-white border border-gray-100 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800" >

            @if($item['placeholder'] !== 'placeholder.webp')
                <div class="h-40 overflow-hidden">
                    <img class="bg-center bg-cover" onerror="this.onerror=null; this.src='{{url('placeholder.webp')}}'" src="{{$item['placeholder']}}" />
                </div>
            @else
                <div class="flex flex-col items-center justify-center overflow-hidden rounded-t-lg" style="background-color: {{$item['color']}}; height: 150px;">
                    <div>
                        <x-icon name="{{$item['icon']}}" class="w-12 h-16 text-white" />
                    </div>
                </div>
            @endif
            <div class="flex gap-4 px-4 my-2 justifiy-between">
                <div class="w-full">
                    <h1 class="font-bold">{{ json_decode($item['name'])->{app()->getLocale()} }}</h1>
                </div>
                <div>
                    <h1>{{ $item['version'] }}</h1>
                </div>
            </div>
            <div class="px-4 h-30">
                <p class="text-gray-600 dark:text-gray-300 text-sm h-30 truncate ...">
                    {{ json_decode($item['description'])->{app()->getLocale()} }}
                </p>
            </div>
            <div class="flex gap-1 px-4 pt-4 my-4 border-t border-gray-100 justifiy-between dark:border-gray-700">
                <div class="flex w-full gap-2 justifiy-start">
                    @if($item['type'] !== 'lib')
                        @if((bool)config('filament-plugins.allow_toggle'))
                            @if($item->active)
                                {{ ($this->disableAction)(['item' => $item]) }}
                            @else
                                {{ ($this->activeAction)(['item' => $item]) }}
                            @endif

                        @endif
                        @if((bool)config('filament-plugins.allow_destroy'))
                            {{ ($this->deleteAction)(['item' => $item])}}
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
