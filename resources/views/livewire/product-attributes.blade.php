<x-form.container>
    @foreach ($items as $attribute)
        <x-form.input>
            <x-slot name="label">
                {{ $attribute->name }}
            </x-slot>
            @php
                $val = $attribute->ValueForProduct($productId);
            @endphp
            @if ($attribute->type == 0)
                <x-common.input.input type="number" name="product_attributes[{{ $attribute->id }}]" value="{{ $old !== null && isset($old[$attribute->id]) ? $old[$attribute->id] : $val }}" />
            @elseif ($attribute->type == 1)
                <x-common.input.input type="text" name="product_attributes[{{ $attribute->id }}]" value="{{ $old !== null && isset($old[$attribute->id]) ? $old[$attribute->id] : $val }}" />
            @elseif ($attribute->type == 2)
                <div class="flex flex-row items-center space-x-2 flex-no-wrap">
                    <label class="space-x-1 whitespace-no-wrap">
                        <input type="radio" {{ $old !== null && isset($old[$attribute->id]) ? ($old[$attribute->id] !== null && $old[$attribute->id] ? 'checked' : '') : ($val !== null && $val ? 'checked' : '') }} name="product_attributes[{{ $attribute->id }}]" value="1"><span>True</span>
                    </label>
                    <label class="space-x-1 whitespace-no-wrap">
                        <input type="radio" {{ $old !== null && isset($old[$attribute->id]) ? ($old[$attribute->id] !== null && !$old[$attribute->id] ? 'checked' : '') : ($val !== null && !$val ? 'checked' : '') }} name="product_attributes[{{ $attribute->id }}]" value="0"><span>False</span>
                    </label>
                </div>
            @elseif ($attribute->type == 3)
                <x-common.input.input type="date" name="product_attributes[{{ $attribute->id }}]" value="{{ $old !== null && isset($old[$attribute->id]) ? $old[$attribute->id] : $val }}" />
            @elseif ($attribute->type == 4)
                <x-common.input.select
                    name="product_attributes[{{ $attribute->id }}]"
                    :required="true"
                    selected="{{ $old !== null && isset($old[$attribute->id]) ? $old[$attribute->id] : ($val !== null ? $val->id : '') }}"
                    :options="($attribute->options !== null ? $attribute->options->map(function($item) {
                        return (object)['key' => $item->id, 'value' => $item->name];
                    })->toArray() : [])"
                />
            @elseif ($attribute->type == 5)
                @livewire('product-option-autocomplete', [
                    'name' => 'product_attributes['.$attribute->id.'][]',
                    'attr' => $attribute,
                    'items' => ($old !== null && isset($old[$attribute->id]) ? $old[$attribute->id] : $val)
                ])
            @endif
            @if ($attribute->measure !== null)
                <span>{{ $attribute->measure->short_name }}</span>
            @endif
        </x-form.input>
    @endforeach
</x-form.container>
