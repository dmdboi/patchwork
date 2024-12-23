@props(['title' => 'Hello World', 'description', 'url', 'button'])

<div>
    <div>
        <div class="px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">{{ $section['data']['title'] }}</h1>
                <p class="mt-4 text-xl text-gray-500">
                    {{ $section['data']['description'] }}
                </p>    

                @if($section['data']['button'])
                <div class="mt-6">
                    <a href=""
                        class="inline-block px-8 py-3 font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                            {{ $section['data']['button'] }}
                        </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
