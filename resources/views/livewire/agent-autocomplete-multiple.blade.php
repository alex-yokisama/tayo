<div>
    <div class="relative">
        <x-common.input.input type="text" wire:keyup.debounce.500ms="autocomplete" wire:model="search"/>
        @if ($suggestions->isNotEmpty())
            <ul class="absolute left-1 top-full border-b shadow-md z-10">
                @foreach ($suggestions as $item)
                    <li class="border-l border-r border-t">
                        <a href="#" wire:click.prevent="add({{ $item->id }})" class="bg-white block px-4 py-0.5 hover:bg-gray-200 focus:bg-gray-200">{{ $item->fullName }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="tagContainer flex flex-row justify-start items-center space-x-2 mt-1">
        @foreach ($items as $item)
            <div class="bg-green-400 rounded-full px-2 py-0.5 text-white text-sm font-bold whitespace-no-wrap">
                <input type="hidden" name="{{ $name }}" value="{{ $item->id }}">
                <span>{{ $item->fullName }}</span>
                <a href="#" wire:click.prevent="remove({{ $item->id }})">&times;</a>
            </div>
        @endforeach
    </div>
</div>
