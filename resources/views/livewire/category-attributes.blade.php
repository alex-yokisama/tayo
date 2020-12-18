<div>
    <table>
        <tr>
            <th>
                Attribute
            </th>
            <th>
                Featured
            </th>
        </tr>
        @foreach ($attributeGroups as $group)
            <tr>
                <td colspan="2" class="px-2 py-0.5 text-center bg-gray-100">{{ $group->name }}</td>
            </tr>
            @foreach ($group->attributes as $attribute)
                <tr>
                    <td class="px-2 py-0.5">
                        @if ($inheritedAttributes->contains($attribute->id))
                            <label class="text-gray-700">
                                <input type="hidden" name="attribute_ids[]" value="{{ $attribute->id }}">
                                <input type="checkbox" checked disabled>
                                {{ $attribute->name }} (inherited)
                            </label>
                        @else
                            <label>
                                <input type="checkbox" name="attribute_ids[]" value="{{ $attribute->id }}"
                                    {{ $ownAttributes->contains($attribute->id) ? 'checked' : '' }}>
                                {{ $attribute->name }}
                            </label>
                        @endif
                    </td>
                    <td class="text-center px-2 py-0.5">
                        <input type="checkbox" name="featured_attributes[]" value="{{ $attribute->id }}"
                            {{ $featuredAttributes->contains($attribute->id) ? 'checked' : '' }}>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="h-2 px-2 py-0.5"></td>
            </tr>
        @endforeach
    </table>
</div>
