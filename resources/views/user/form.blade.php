<x-custom-layout>

    <x-slot name="title">
        Role
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Role</x-common.h.h1>
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
                value="{{ $errors->any() ? (old('name')) : (($item !== null) ? ($item->name) : '') }}" />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Email
                </x-slot>
                <x-common.input.input type="email" name="email"
                value="{{ $errors->any() ? (old('email')) : (($item !== null) ? ($item->email) : '') }}" />
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Password
                </x-slot>
                <x-common.input.input type="password" name="password"/>
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Confirm password
                </x-slot>
                <x-common.input.input type="password" name="password_confirmation"/>
            </x-form.input>
            <x-form.input>
                <x-slot name="label">
                    Roles
                </x-slot>
                <div class="grid gap-1 gap-x-4 grid-cols-2 md:grid-cols-3">
                    @foreach ($roles as $role)
                        <label>
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                {{
                                    (old('roles') !== null) ?
                                    (collect(old('roles'))->contains($role->id) ? 'checked' : '') :
                                    (($item !== null && $item->roles->contains($role)) ? 'checked' : '')
                                }}
                            >
                            {{ $role->name }}
                        </label>
                    @endforeach
                </div>
            </x-form.input>
        </x-form.container>
    </form>
</x-custom-layout>
