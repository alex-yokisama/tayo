<a href="{{ $href }}" {{ $attributes->merge(['class' => 'bg-green-400 text-white font-bold uppercase py-1 px-5 shadow-md hover:shadow-none focus:shadow-none hover:bg-opacity-75 focus:bg-opacity-75']) }}>
    {{ $slot }}
</a>
