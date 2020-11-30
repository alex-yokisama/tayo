<x-custom-layout>

    <x-slot name="title">
        Category
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Category</x-common.h.h1>
            <x-common.button.group>
                <x-common.a.a :href="$backUrl">Cancel</x-common.a.a>
                <x-common.button.a href="#" x-data="{}" @click.prevent="$dispatch('submit-save-form')">Save</x-common.button.a>
            </x-common.button.group>
        </div>

        @if (session('status') == 'success')
            <x-common.alert.success>
                {{ session('message') }}
            </x-common.alert.success>
        @endif

        @if ($errors->any())
            <div class="space-y-1">
                @foreach ($errors->all() as $error)
                    <x-common.alert.error>
                        {{ $error }}
                    </x-common.alert.error>
                @endforeach
            </div>
        @endif
    </x-slot>

    <form class="editItemForm" action="" method="post" x-data="{}" @submit-save-form.window="document.querySelector('form.editItemForm').submit()">
        @csrf
        <input type="hidden" name="id" value="{{ isset($item) ? $item->id : ''}}">
        <input type="hidden" name="backUrl" value="{{ $backUrl }}">
        <x-form.container>
            <x-form.input>
                <x-slot name="label">
                    Name
                </x-slot>
                <x-common.input.input type="text" name="name"
                value="{{ (old('name') !== null) ? (old('name')) : (($item != null) ? ($item->name) : '') }}" />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Parent
                </x-slot>
                <select class="border-2 px-2 py-0.5" name="parent">
                    <option value="0">-- select --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{
                                (old('parent') !== null) ?
                                ((old('parent') == $category->id) ? 'selected' : '') :
                                (
                                    ($item !== null) ?
                                    (($item->parent !== null && $item->parent->id == $category->id) ? 'selected' : '') :
                                    ($parent == $category->id ? 'selected' : '')
                                )
                            }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Attributes
                </x-slot>
                <div class="grid gap-1 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($attributes as $attribute)
                        <label>
                            <input type="checkbox" name="attribute_ids[]" value="{{ $attribute->id }}"
                                {{
                                    (old('parent') !== null) ?
                                    (collect(old('attribute_ids'))->contains($attribute->id) ? 'checked' : '') :
                                    (($item !== null && $item->attributes->contains($attribute)) ? 'checked' : '')
                                }}
                            >
                            {{ $attribute->name }}
                        </label>
                    @endforeach
                </div>
            </x-form.input>
        </x-form.container>
    </form>
</x-custom-layout>
