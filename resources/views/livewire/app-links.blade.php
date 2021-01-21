<div>
    <x-common.table.table>
        <x-slot name="thead">
            <x-common.table.th>App&nbsp;store&nbsp;name</x-common.table.th>
            <x-common.table.th>url</x-common.table.th>
            <x-common.table.th>Os</x-common.table.th>
            <x-common.table.th>Price</x-common.table.th>
            <x-common.table.th></x-common.table.th>
        </x-slot>
        @foreach ($links as $link)
            @continue($link === null)
            <x-common.table.tr>
                <x-common.table.td class="align-top">
                    <x-common.input.input type="text" name="links[{{ $loop->index }}][app_store_name]" value="{{ $link->app_store_name }}" />
                </x-common.table.td>
                <x-common.table.td class="align-top">
                    <x-common.input.input type="text" name="links[{{ $loop->index }}][url]" value="{{ $link->url }}" />
                </x-common.table.td>
                <x-common.table.td class="align-top">
                    @livewire('os-autocomplete', ['name' => 'links['.$loop->index.'][os]', 'item' => $link->os, 'anyCategory' => true], key('os_'.$loop->index))
                </x-common.table.td>
                <x-common.table.td class="align-top">
                    <x-common.input.input type="number" min="0" name="links[{{ $loop->index }}][price]" value="{{ $link->price }}" />
                </x-common.table.td>
                <x-common.table.td class="align-top">
                    <x-common.a.a href="#" wire:click.prevent="remove({{ $loop->index }})" class="text-red-500">delete</x-common.a.a>
                </x-common.table.td>
            </x-common.table.tr>
        @endforeach
    </x-common.table.table>

    <x-common.button.group class="mt-2">
        <x-common.button.a href="#" wire:click.prevent="add">add</x-common.button.a>
    </x-common.button.group>
</div>
