<x-custom-layout>

    <x-slot name="title">
        App
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>App</x-common.h.h1>
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

    <form class="editItemForm overflow-x-auto overflow-y-visible" action="" method="post" x-data="{}" @submit-save-form.window="document.querySelector('form.editItemForm').submit()">
        @csrf
        <input type="hidden" name="id" value="{{ (!$is_copy && $item !== null) ? $item->id : ''}}">
        <input type="hidden" name="backUrl" value="{{ $backUrl }}">
        <x-common.tabs>
            <x-slot name="General">
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
                            Type
                        </x-slot>
                        <x-common.input.select
                            name="type"
                            :required="true"
                            :selected="old('type') !== null ? old('type') : ($item !== null ? $item->type->id : null)"
                            :options="($types->map(function($item, $index) {
                                return (object)['key' => $index, 'value' => $item];
                            })->toArray())"
                        />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Price
                        </x-slot>
                        <x-common.input.input type="number" min="0" name="price"
                        value="{{ ($errors->any()) ? (old('price')) : (($item != null) ? ($item->price) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Change log URL
                        </x-slot>
                        <x-common.input.input type="text" name="change_log_url"
                        value="{{ (old('change_log_url') !== null) ? (old('change_log_url')) : (($item != null) ? ($item->change_log_url) : '') }}" />
                    </x-form.input>
                </x-form.container>
            </x-slot>

            <x-slot name="Images">
                <x-form.container>
                    <x-form.input>
                        @livewire('item-images', [
                            'name' => 'images[]',
                            'multiple' => true,
                            'images' => ($errors->any() ?
                                            (old('images') ? old('images') : []) :
                                            ($item !== null && $item->images !== null ? $item->images->map(function($item) {
                                                return $item->path;
                                            }) : []))])
                    </x-form.input>
                </x-form.container>
            </x-slot>

            <x-slot name="Relations">
                <x-form.container>
                    <x-form.input>
                        <x-slot name="label">
                            Brand
                        </x-slot>
                        <x-common.input.select
                            name="brand"
                            :required="true"
                            :selected="$errors->any() ? old('brand') : ($item !== null && $item->brand !== null ? $item->brand->id : null)"
                            :options="($brands->map(function($item) {
                                return (object)['key' => $item->id, 'value' => $item->name];
                            })->toArray())"
                        />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Countries
                        </x-slot>
                        @livewire('country-autocomplete-multiple', ['name' => 'countries[]', 'items' => ($errors->any() ? old('countries') : ($item !== null ? $item->countries : []))])
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            OS
                        </x-slot>
                        @livewire('os-autocomplete-multiple', [
                            'name' => 'os[]',
                            'items' => ($errors->any() ? old('os') : ($item !== null ? $item->os : []))])
                    </x-form.input>
                </x-form.container>
                <div class="spacer h-40"></div>
            </x-slot>

            <x-slot name="Links">
                @if ($item !== null)
                    @livewire('app-links', ['links' => $item->links->map(function($item) {
                        return [
                            'os' => $item->os->id,
                            'price' => $item->price,
                            'app_store_name' => $item->app_store_name,
                            'url' => $item->url
                        ];
                    })->toArray()])
                @else
                    @livewire('app-links', ['links' => []])
                @endif
                <div class="spacer h-40"></div>
            </x-slot>
        </x-common.tabs>
    </form>
</x-custom-layout>
