@props(['title' => false])

<div x-data="{show: true}" x-show="show" {{ $attributes->merge(['class' => 'px-6 py-3 relative']) }}>
    <span @click="show = false" class="absolute top-0 right-0 text-2xl mx-3 font-bold cursor-pointer">&times;</span>
    @if ($title)
        <x-common.h.h4>
            {{ $title }}
        </x-common.h.h4>
    @endif
    <p>
        {{ $slot }}
    </p>
</div>
