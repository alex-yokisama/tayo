@props(['name', 'options' => [], 'selected' => null, 'required' => false, 'default' => '-- select --'])

<select name="{{ $name }}" {{ $attributes->merge(['class' => 'border px-2 py-0.5']) }}>
    <option {{ $required ? '' : 'value="0"'}} >{{ $default }}</option>
    @foreach ($options as $option)
        <option value="{{ $option->key }}" {{ $selected == $option->key ? 'selected' : '' }}>{{ $option->value }}</option>
    @endforeach
</select>