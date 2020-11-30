@props(['title' => 'Success'])

<x-common.alert.alert {{ $attributes->merge(['class' => 'text-green-500 bg-green-100']) }}>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    {{ $slot }}
</x-common.alert.alert>
