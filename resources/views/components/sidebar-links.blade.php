<ul>
    @foreach ($sidebarLinks as $link)
        <li class="{{ $link->active ? 'border-gray-200 border-l-2 text-white bg-gray-800' : 'text-gray-300 hover:text-white hover:bg-gray-800' }} uppercase ">
            <a href="{{ $link->path }}" class="px-6 py-3 block">{{ $link->name }}</a>
        </li>
    @endforeach
</ul>
