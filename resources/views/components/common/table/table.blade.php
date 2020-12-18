<table {{ $attributes->merge(['class' => 'table table-auto w-full border-collapse']) }}>
    @if (isset($thead))
        <thead class="bg-gray-900 text-white">
            <tr>
                {{ $thead }}
            </tr>
        </thead>
    @endif    
    <tbody>
        {{ $slot }}
    </tbody>
</table>
