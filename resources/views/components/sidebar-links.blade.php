<ul>
    @foreach ($sidebarLinks as $linkGroup)
        <li x-data="{ show: {{ $linkGroup->active ? 'true' : 'false' }} }" class="uppercase">
            <a href="#" class="uppercase px-6 py-3 flex flex-row space-x-2 flex-no-wrap items-center" x-bind:class="{ 'text-white bg-gray-800': show, 'text-gray-300 hover:text-white hover:bg-gray-800': !show }" x-on:click.prevent="show = !show">
                <span>{{ $linkGroup->name }}</span>
                </span>
                    <x-common.arrow.sort-up class="border-white" x-show="show" />
                    <x-common.arrow.sort-down class="border-white" x-show="!show" />
                </span>
            </a>
            <ul x-show="show" class="mb-5 text-sm">
                @foreach ($linkGroup->items as $link)
                    <li class="{{ $link->active ? 'border-gray-200 border-l-2 text-white bg-gray-800' : 'text-gray-300 hover:text-white hover:bg-gray-800' }} pl-2">
                        <a href="{{ $link->path }}" class="px-6 py-3 block">{{ $link->name }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach
</ul>
