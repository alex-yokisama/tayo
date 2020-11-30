<div class="p-2 pr-0" x-data={show:false}>
    <div class="flex flex-row items-center justify-between">
        <x-common.button.group @click="show = !show"
            class="{{ $category->children->count() > 0 ? 'cursor-pointer text-blue-800' : '' }}">
            <span>{{ $category->name }}</span>
            @if ($category->children->count() > 0)
                <x-common.arrow.sort-down x-show="!show" class="border-blue-800" />
                <x-common.arrow.sort-up x-show="show" class="border-blue-800" style="display: none;" />
            @endif
        </x-common.button.group>
        <x-common.button.group>
            <x-common.a.a href="#" class="text-red-500" @click="$dispatch('show-delete-category-modal', {{ $category->id }})">delete</x-common.a.a>
            <x-common.button.a href="/admin/category?id={{ $category->id }}&backUrl={{ urlencode($backUrl) }}">Edit</x-common.button.a>
            <x-common.button.a href="/admin/category?parent={{ $category->id }}&backUrl={{ urlencode($backUrl) }}">New child</x-common.button.a>
        </x-common.button.group>
    </div>
    <div class="border-b-2 pt-2" ></div>

    @if ($category->children->count() > 0)
        <div class="p-2 pr-0 border-l-2" style="display:none;" x-show="show">
            @foreach ($category->children as $child)
                <x-category.tree :category=$child backUrl="{{ $backUrl }}" />
            @endforeach
        </div>
    @endif
</div>
