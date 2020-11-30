<div class="" x-data="{open: false}">
    <div class="cursor-pointer flex flex-row flex-no-wrap items-center justify-between bg-gray-900 text-white px-4 py-2" @click="open = open ^ true">
        <x-common.h.h4>Search</x-common.h.h4>
        <span class="text-sm"  x-show="open" style="display: none;">Hide</span>
        <span class="text-sm"  x-show="!open">Show</span>
    </div>
    <div style="display:none;" class="border-b-2 border-gray-300 px-4 py-2" x-show="open">
        <form class="space-y-2" action="" method="get">
            <input type="hidden" name="perPage" value="{{ Request()->perPage }}">
            <input type="hidden" name="sort" value="{{ Request()->sort }}">
            <input type="hidden" name="order" value="{{ Request()->order }}">

            {{ $slot }}

            <div class="flex justify-end">
                <x-common.button.submit value="Submit" />
            </div>
        </form>
    </div>
</div>
