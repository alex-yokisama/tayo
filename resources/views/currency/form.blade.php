<x-custom-layout>

    <x-slot name="title">
        Currency
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Currency</x-common.h.h1>
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
                    Symbol
                </x-slot>
                <x-common.input.input type="text" name="symbol"
                value="{{ (old('symbol') !== null) ? (old('symbol')) : (($item != null) ? ($item->symbol) : '')}}" />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Country
                </x-slot>
                <select class="border-2 px-2 py-0.5" name="country">
                    <option>-- select --</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}"
                            {{
                                (old('country') !== null) ?
                                ((old('country') == $country->id) ? 'selected' : '') :
                                (($item !== null && $item->country !== null && $item->country->id == $country->id) ? 'selected' : '')
                            }}
                        >
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </x-form.input>
        </x-form.container>
    </form>
</x-custom-layout>
