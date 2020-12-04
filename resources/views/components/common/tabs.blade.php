@php
    $tabs = collect($__laravel_slots)->filter(function($item, $key) {
        return $key != "__default";
    });
@endphp

<div class="tabs overflow-x-auto overflow-y-visible" x-data="{active: '{{ $tabs->keys()[0] }}'}">
    <ul class="buttons flex flex-row items-end justify-start">
        @foreach ($tabs as $key => $value)
            <li class="text-bold" x-bind:class="{
                'border-t-2 border-l-2 border-r-2': active == '{{ $key }}',
                'border-b-2': active != '{{ $key }}'
            }">
                <a class="px-6 py-2 block" href="#" @click.prevent="active = '{{ $key }}'">{{ $key }}</a>
            </li>
        @endforeach
        <li class="flex-grow border-b-2"></li>
    </ul>
    <div class="content py-4 overflow-x-auto overflow-y-visible">
        @foreach ($tabs as $key => $value)
            <div x-show="active == '{{ $key }}'" style="display: none;">
                {{ $value }}
            </div>
        @endforeach
</div>
