@props(['title' => 'Error'])

<x-common.alert.alert {{ $attributes->merge(['class' => 'text-red-500 bg-red-100']) }}>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    {{ $slot }}
</x-common.alert.alert>
