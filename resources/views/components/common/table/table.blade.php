<table {{ $attributes->merge(['class' => 'table table-auto w-full border-collapse']) }}>
    <thead class="bg-gray-900 text-white">
        <tr>
            {{ $thead }}
        </tr>
    </thead>
    <tbody>
        {{ $slot }}
    </tbody>
</table>
