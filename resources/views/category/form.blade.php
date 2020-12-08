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
                <select x-data="categorySelect()" x-ref='categorySelect' x-on:change="categoryChanged($refs)" x-init="categoryChanged($refs)" class="border px-2 py-0.5" name="parent">
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
                @once
                    @push('footerScripts')
                        <script>
                            function categorySelect() {
                                return {
                                    categoryChanged($refs) {
                                        Livewire.emit('categoryChanged', $refs.categorySelect.value);
                                    }
                                }
                            }
                        </script>
                    @endpush
                @endonce
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Attributes
                </x-slot>
                @livewire('category-attributes', [
                    'name' => 'attribute_ids',
                    'categoryId' => ($errors->any() ?
                                    (old('parent')) :
                                    ($item === null ?
                                        $parent :
                                        ($item->parent !== null ? $item->parent->id : null))),
                    'ownAttributes' => ($errors->any() ?
                                        (old('attribute_ids')) :
                                        ($item !== null ? $item->attributes->map(function($item) {
                                            return $item->id;
                                        })->toArray() : []))
                ])
            </x-form.input>
        </x-form.container>
    </form>
</x-custom-layout>
