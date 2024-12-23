<div class="p-4">
    <section class="grid w-full max-w-3xl py-8 mx-auto font-mono md:grid-cols-3">
        <div class="col-span-1 text-xl">
            <h2>{{ $section['data']['title'] }}</h2>
        </div>
        <div class="col-span-1 md:col-span-2">

        <x-collection-menu :section="$section" />
        </div>
    </section>
</div>
