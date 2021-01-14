<x-custom-layout>

    <x-slot name="title">
        Agent
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Agent</x-common.h.h1>
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
                value="{{ (old('name') !== null) ? (old('name')) : (($item != null) ? ($item->name) : '')}}" />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Surname
                </x-slot>
                <x-common.input.input type="text" name="surname"
                value="{{ (old('surname') !== null) ? (old('surname')) : (($item != null) ? ($item->surname) : '')}}" />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Website
                </x-slot>
                <x-common.input.input type="text" name="website"
                value="{{ (old('website') !== null) ? (old('website')) : (($item != null) ? ($item->website) : '')}}" />
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
                <label class="space-x-1">
                    <span>Is retailer</span>
                    <input type="checkbox" name="is_retailer" value="1"
                    {{ (old('name') !== null) ? ((old('is_retailer')) ? 'checked' : '') : (($item !== null && $item->is_retailer) ? 'checked' : '') }} />
                </label>
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Logo
                </x-slot>
                @livewire('item-images', [
                    'name' => 'image',
                    'multiple' => false,
                    'images' => ($errors->any() ? (old('image') ? [old('image')] : []) : ($item !== null && $item->image !== null ? [$item->image] : []))])
            </x-form.input>
        </x-form.container>
    </form>
</x-custom-layout>
