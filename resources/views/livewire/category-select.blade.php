<x-form.input>
    <x-slot name="label">
        Category
    </x-slot>
    <x-common.input.select
        wire:change="categoryChanged"
        wire:model="itemId"
        wire:init.debounce.500ms="categoryChanged"
        name="{{ $name }}"
        :required="true"
        :options="($options->map(function($item) {
            return (object)['key' => $item->id, 'value' => $item->name];
        })->toArray())"
    />
</x-form.input>
