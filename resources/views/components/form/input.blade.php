<tr {{ $attributes->merge(['class' => '']) }}>
    @if (isset($label))
        <td class="px-4 py-2 align-top">
            {{ $label }}
        </td>
        <td class="px-4 py-2 align-top">
            {{ $slot }}
        </td>
    @else
        <td colspan=2 class="px-4 py-2 align-top">
            {{ $slot }}
        </td>
    @endif
</tr>
