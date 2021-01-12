<div>
    <x-common.tabs>
        @foreach ($kinds as $kind)
            <x-slot :name="$kind->name">
                <x-form.container>
                    @if ($kind->groups->count() > 0)
                        <x-form.input class="text-bold">
                            <x-slot name="label">
                                Attribute
                            </x-slot>
                            Featured
                        </x-form.input>
                    @endif
                    @foreach ($kind->groups as $group)
                        <x-form.input class="bg-gray-200">
                            {{ $group->name}}
                        </x-form.input>
                        @foreach ($group->attributes as $attribute)
                            <x-form.input>
                                <x-slot name="label">
                                    @if ($inheritedAttributes->contains($attribute->id))
                                        <div class="text-gray-700">
                                            <input type="hidden" name="attribute_ids[]" value="{{ $attribute->id }}">
                                            <input type="checkbox" checked disabled>
                                            {{ $attribute->name }} (inherited)
                                        </div>
                                    @else
                                        <div>
                                            <input type="checkbox" name="attribute_ids[]" value="{{ $attribute->id }}"
                                                {{ $ownAttributes->contains($attribute->id) ? 'checked' : '' }}>
                                            {{ $attribute->name }}
                                        </div>
                                    @endif
                                </x-slot>

                                <input type="checkbox" name="featured_attributes[]" value="{{ $attribute->id }}"
                                    {{ $featuredAttributes->contains($attribute->id) ? 'checked' : '' }}>
                            </x-form.input>
                        @endforeach
                    @endforeach
                </x-form.container>
            </x-slot>
        @endforeach
    </x-common.tabs>
</div>
