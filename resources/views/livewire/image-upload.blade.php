<div>
    @if ($errors->any())
        <div class="space-y-1 mb-1">
            @foreach ($errors->all() as $error)
                <x-common.alert.error>
                    {{ $error }}
                </x-common.alert.error>
            @endforeach
        </div>
    @endif
    <div class=" border-2 border-gray-500 border-dashed text-gray-500 relative flex items-center justify-center w-80 h-24 bg-gray-200">
        <span class="text-sm font-bold uppercase text-center">Click or drag your files here</span>
        <input type="file" id="{{ rand() }}" wire:model="files" class="absolute inset-0 z-40 opacity-0" multiple accept="image/*">
    </div>
</div>
