<div>
    @if ($inheritedAttributes->count() > 0)
        <x-common.h.h4>Inherited from parent</x-common.h.h4>
        <div class="grid gap-1 gap-x-4 grid-cols-2 md:grid-cols-3">
            @foreach ($inheritedAttributes as $attribute)
                <label>
                    <input type="checkbox" checked disabled>
                    {{ $attribute->name }}
                </label>
            @endforeach
        </div>
    @endif
    @if ($availableAttributes->count() > 0)
        <x-common.h.h4>Available attributes</x-common.h.h4>
        <div class="grid gap-1 gap-x-4 grid-cols-2 md:grid-cols-3">
            @foreach ($availableAttributes as $attribute)
                <label>
                    <input type="checkbox" name="attribute_ids[]" value="{{ $attribute->id }}"
                        {{ $ownAttributes->contains($attribute->id) ? 'checked' : '' }}>
                    {{ $attribute->name }}
                </label>
            @endforeach
        </div>
    @endif
</div>
