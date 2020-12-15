<x-custom-layout>

    <x-slot name="title">
        Brand
    </x-slot>

    <x-slot name="sidebarLinks">
        <x-sidebar-links :sidebarLinks=$sidebarLinks />
    </x-slot>

    <x-slot name="top">
        <div class="flex flex-row items-center justify-between">
            <x-common.h.h1>Brand</x-common.h.h1>
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

    <form class="editItemForm overflow-x-auto" action="" method="post" x-data="{}" @submit-save-form.window="document.querySelector('form.editItemForm').submit()">
        @csrf
        <input type="hidden" name="id" value="{{ isset($item) ? $item->id : ''}}">
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
                            Website
                        </x-slot>
                        <x-common.input.input type="text" name="website"
                        value="{{ (old('website') !== null) ? (old('website')) : (($item != null) ? ($item->website) : '') }}" />
                    </x-form.input>
                    <x-form.input>
                        <x-slot name="label">
                            Bio
                        </x-slot>
                        <x-common.input.textarea name="bio"
                            value="{{ (old('bio') !== null) ? (old('bio')) : (($item != null) ? ($item->bio) : '') }}"
                        />
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
            </x-slot>
            <x-slot name="Contacts">
                <div x-data='brandContacts(
                    @json(
                        $errors->any() ?
                        old('contacts') :
                        ($item !== null ? $item->contacts : [])
                    )
                )'>
                    <x-common.table.table>
                        <x-slot name="thead">
                            <x-common.table.th>Name</x-common.table.th>
                            <x-common.table.th>Role</x-common.table.th>
                            <x-common.table.th>Email</x-common.table.th>
                            <x-common.table.th>Phone</x-common.table.th>
                            <x-common.table.th></x-common.table.th>
                        </x-slot>
                        <template x-for="(item, index) in items" :key="item">
                            <x-common.table.tr>
                                <x-common.table.td>
                                    <x-common.input.input type="text" x-bind:name="`contacts[${index}][name]`" x-model="items[index].name" />
                                </x-common.table.td>
                                <x-common.table.td>
                                    <x-common.input.input type="text" x-bind:name="`contacts[${index}][role]`" x-model="items[index].role" />
                                </x-common.table.td>
                                <x-common.table.td>
                                    <x-common.input.input type="email" x-bind:name="`contacts[${index}][email]`" x-model="items[index].email" />
                                </x-common.table.td>
                                <x-common.table.td>
                                    <x-common.input.input type="text" x-bind:name="`contacts[${index}][phone]`" x-model="items[index].phone" />
                                </x-common.table.td>
                                <x-common.table.td>
                                    <x-common.a.a href="#" class="text-red-500" x-on:click.prevent="remove(index)">remove</x-common.a.a>
                                </x-common.table.td>
                            </x-common.table.tr>
                        </template>
                    </x-common.table.table>
                    <x-common.button.group class="my-2">
                        <x-common.button.a href="#" x-on:click.prevent="add">Add</x-common.button.a>
                    </x-common.button.group>
                </div>
                <script>
                    function brandContacts(items) {
                        return {
                            items: items,
                            add() {
                                this.items.push({});
                            },
                            remove(index) {
                                this.items.splice(index, 1);
                            }
                        }
                    }
                </script>
            </x-slot>
        </x-common.tabs>
    </form>
</x-custom-layout>
