@props(['name', 'value'])

<div x-data="textarea()">
    <textarea {{ $attributes->merge(['class' => 'block border resize-none px-2 py-0.5']) }} x-on:keyup.debounce.500ms="autoresize" name="{{ $name }}" cols="50">{{ trim($value) }}</textarea>
</div>

@once
    @push('footerScripts')
        <script>
            function textarea() {
                return {
                    autoresize($event) {
                        let element = $event.target;
                        element.style.height = "1px";
                        element.style.height = (28 + element.scrollHeight) + "px";
                    }
                }
            }
        </script>
    @endpush
@endonce
