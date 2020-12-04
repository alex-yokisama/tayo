@props(['name', 'options' => [], 'selected' => []])

<select multiple name="{{ $name }}" {{ $attributes->merge(['class' => 'border px-2 py-0.5']) }}>
    @foreach ($options as $option)
        <option value="{{ $option->key }}" {{ collect($selected)->contains($option->key) ? 'selected' : '' }}>{{ $option->value }}</option>
    @endforeach
</select>
